<?php

namespace App\User\Request\Auth;

use App\Infra\Request\RequestObject\RequestObjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

class LoginRequest implements RequestObjectInterface
{
    /**
     * @var string
     * @Assert\Email(message="Wrong format of email")
     * @Assert\NotBlank(message="Email is required")
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank(message="Password is required")
     */
    private $password;

    /**
     * LoginRequest constructor.
     * @param string $email
     * @param string $password
     */
    public function __construct(string $email = '', string $password = '')
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
