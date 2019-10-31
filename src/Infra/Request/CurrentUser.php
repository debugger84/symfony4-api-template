<?php

namespace App\Infra\Request;

class CurrentUser implements CurrentUserInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * CurrentUser constructor.
     * @param int $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }


    public function getId(): string
    {
        return $this->getId();
    }
}
