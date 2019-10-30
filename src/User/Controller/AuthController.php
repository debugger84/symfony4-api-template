<?php

namespace App\User\Controller;

use App\Infra\Response\DataResponse;
use App\Infra\Response\ErrorResponse;
use App\User\Request\Auth\LoginRequest;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\LcobucciJWTEncoder;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Example\Controller
 * @Route("/auth", name="auth_")
 * @SWG\Tag(name="user")
 */
class AuthController
{
    /**
     * Login the user
     * @Route("/login", name="get_all", methods={"POST"})
     * @param LoginRequest $request
     * @param JWTEncoderInterface $encoder
     * @return Response
     * @throws JWTEncodeFailureException
     * @SWG\Response(
     *     response=200,
     *     description="Returns a list of cities",
     *     @SWG\Schema(
     *        type="object"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Wrong password or email",
     *     @SWG\Schema(
     *        type="object"
     *     )
     * )
     */
    public function loginWithPassword(LoginRequest $request, LcobucciJWTEncoder $encoder)
    {
        // simple method of authentication for test purposes
        if ($request->getEmail() !== 'test@test.com' || $request->getPassword() !== 'test') {
            return new ErrorResponse('Wrong password or email');
        }
        $payload = [
            'id' => 1,
            'grants' => ['all'],
        ];
        $token = $encoder->encode($payload);
        return new DataResponse([
            'token' => $token
        ]);
    }
}
