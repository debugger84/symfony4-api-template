<?php

namespace App\Infra\EventListener;

use App\Infra\Request\Exception\InvalidRequestDataException;
use App\Infra\Request\Exception\UserRequiredException;
use App\Infra\Exception\CriticalException;
use App\Infra\Exception\JwtTokenException;
use App\Infra\Exception\JwtTokenExpiredException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ApiExceptionSubscriber constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    public function onKernelException(ExceptionEvent $event)
    {
        $e = $event->getException();
        $data = [
            'status' => false,
            'error' => $e->getMessage(),
        ];
        $code = 500;

        if ($e instanceof JwtTokenException) {
            $code = 401;
        } elseif ($e instanceof UserRequiredException) {
            $code = 403;
        } elseif ($e instanceof NotFoundHttpException) {
            $code = 404;
        } elseif ($e instanceof InvalidRequestDataException) {
            $code = 400;
            $data['errors'] = $e->getData();
        } elseif ($e instanceof AccessDeniedHttpException) {
            $code = 403;
        } elseif ($e instanceof HttpException) {
            $code = $e->getStatusCode();
        }

        $response = new JsonResponse(
            $data,
            $code
        );
        $response->headers->set('Content-Type', 'application/problem+json');
        $response->headers->add(['Access-Control-Allow-Origin' => [
            '*'
        ]]);
        $logContext = [
            'uri' => $this->getUri($event),
            'body' => $event->getRequest()->getContent(),
        ];
        if ($e instanceof InvalidRequestDataException) {
            $this->logger->debug($e->getMessage(), $logContext);
        } elseif ($e instanceof JwtTokenExpiredException) {
            $this->logger->info($e->getMessage(), $logContext);
        } elseif ($e instanceof CriticalException) {
            $this->logger->critical($e->getMessage(), $logContext + [
                'exception' => $e
            ]);
        } elseif ($e instanceof ResourceNotFoundException) {
            $this->logger->warning($e->getMessage(), $logContext + [
                    'exception' => $e
                ]);
        } else {
            $this->logger->error($e->getMessage(), $logContext + [
                'exception' => $e
            ]);
        }

        $event->setResponse($response);
    }

    private function getUri(ExceptionEvent $event)
    {
        return str_replace('://', '---', $event->getRequest()->getUri());
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }
}
