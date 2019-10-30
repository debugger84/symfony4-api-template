<?php


namespace App\User\Entity;


use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUser as BaseJWTUser;

class JwtUser extends BaseJWTUser
{
    public static function createFromPayload($username, array $payload)
    {
        return new self($username, $payload['grants']);
    }

    public function getUserId()
    {
        return $this->getUsername();
    }
}
