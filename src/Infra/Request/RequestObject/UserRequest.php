<?php

namespace App\Infra\Request\RequestObject;

use App\Infra\Request\CurrentUserInterface;

class UserRequest implements UserRequestObjectInterface
{
    /**
     * @var CurrentUserInterface
     */
    private $user;

    public function getUser(): CurrentUserInterface
    {
        return $this->user;
    }

    public function setUser(CurrentUserInterface $user): self
    {
        $this->user = $user;
        return $this;
    }
}
