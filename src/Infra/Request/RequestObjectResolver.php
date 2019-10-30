<?php

namespace App\Infra\Request;

use App\Infra\Request\Exception\InvalidRequestDataException;
use App\Infra\Request\Exception\UserRequiredException;
use App\Infra\Request\RequestObject\LoggableRequestInterface;
use App\Infra\Request\RequestObject\RequestObjectInterface;
use App\Infra\Request\RequestObject\UserRequestObjectInterface;
use App\User\Entity\JwtUser;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Generator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class RequestObjectResolver implements ArgumentValueResolverInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var TokenStorageInterface
     */
    private $storage;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RequestObjectResolver constructor.
     * @param ValidatorInterface $validator
     * @param TokenStorageInterface $storage
     * @param LoggerInterface $logger
     */
    public function __construct(ValidatorInterface $validator, TokenStorageInterface $storage, LoggerInterface $logger)
    {
        $this->validator = $validator;
        $this->storage = $storage;
        $this->logger = $logger;
    }


    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return is_subclass_of($argument->getType(), RequestObjectInterface::class);
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return Generator
     * @throws ExceptionInterface
     * @throws InvalidRequestDataException
     * @throws UserRequiredException
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $data = $this->getDataFromRequest($request);
        $normalizer = new PropertyNormalizer();
        $dto = $normalizer->denormalize($data, $argument->getType());

        $this->logRequest($request, $data, $dto);

        if ($dto instanceof UserRequestObjectInterface) {
            $user = $this->getCurrentUser();
            if (!$user) {
                throw new UserRequiredException('User is not defined.');
            }
            /** @var UserRequestObjectInterface $dto */
            $dto->setUser($user);
        }

        $messages = $this->validateDTO($dto);
        if ($messages) {
            $e = new InvalidRequestDataException($messages);
            $this->logError($request, $data, $dto, $e);
            throw $e;
        }

        yield $dto;
    }

    private function logError(Request $request, array $data, $dto, Exception $e): void
    {
        if ($dto instanceof LoggableRequestInterface) {
            $method = $dto->getLogLevelOfErrors();
            $context = [
                'uri' => $this->getUri($request),
                'data' => $data,
                'message' => $e->getMessage(),
            ];
            if ($e instanceof InvalidRequestDataException) {
                $context['errors'] = $e->getData();
            }
            $this->log('Error was occurred in request', $context, $method);
        }
    }

    private function logRequest(Request $request, array $data, $dto): void
    {
        if ($dto instanceof LoggableRequestInterface) {
            $method = $dto->getLogLevelOfRequest();
            $this->log('Logging of request', [
                'uri' => $this->getUri($request),
                'data' => $data
            ], $method);
        }
    }

    private function getDataFromRequest(Request $request): array
    {
        $data = $request->request->all();
        if (!$data && (
                Request::METHOD_POST === $request->getMethod() ||
                Request::METHOD_PUT === $request->getMethod())
        ) {
            $data = json_decode($request->getContent(), true);
        }
        if (Request::METHOD_GET === $request->getMethod()) {
            $data = $request->query->all();
        }
        if (!$data) {
            $data = [];
        }

        return $data;
    }

    private function getUri(Request $request): string
    {
        return str_replace('://', '---', $request->getUri());
    }

    private function log(string $message, array $context, $method): string
    {
        if (method_exists($this->logger, $method)) {
            $this->logger->$method($message, $context);
        }
    }

    /**
     * @param $dto
     * @return array
     */
    private function validateDTO($dto): array
    {
        /** @var ConstraintViolationList $errors */
        $errors = $this->validator->validate($dto);
        if (0 !== count($errors)) {
            $messages = [];
            foreach ($errors as $error) {
                $property = $error->getPropertyPath();
                $messages[$property] = $error->getMessage();
            }

            return $messages;
        }

        return [];
    }

    protected function getCurrentUser(): ?CurrentUserInterface
    {
        $token = $this->storage->getToken();
        if ($token instanceof TokenInterface) {
            /** @var JwtUser $user */
            $user = $token->getUser();
            if ($user instanceof JwtUser) {
                return new CurrentUser($user->getUserId());
            }
        }

        return null;
    }
}
