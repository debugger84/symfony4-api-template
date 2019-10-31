<?php

namespace App\User\Entity;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUser as BaseJWTUser;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

class JwtUser extends BaseJWTUser
{
    /**
     * @param $username
     * @param array $payload
     * @return JwtUser|BaseJWTUser|JWTUserInterface
     */
    public static function createFromPayload($username, array $payload)
    {
        return new self($username, $payload['grants']);
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->getUsername();
    }
}
