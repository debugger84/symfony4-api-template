<?php

namespace App\Infra\Request\RequestObject;

use App\Infra\Request\CurrentUserInterface;

interface UserRequestObjectInterface extends RequestObjectInterface
{
    public function getUser(): CurrentUserInterface;
    public function setUser(CurrentUserInterface $user);
}
