<?php

namespace App\Infra\Request;

class CurrentUser implements CurrentUserInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * CurrentUser constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }


    public function getId(): int
    {
        return $this->getId();
    }
}
