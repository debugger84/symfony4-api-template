<?php

namespace App\Documentation\Request\Document;

use App\Infra\Request\RequestObject\RequestObjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

class GetListRequest implements RequestObjectInterface
{
    /**
     * @var int
     * @Assert\GreaterThan(value="0", message="The page should be positive")
     */
    private $page = 1;

    /**
     * @var int
     * @Assert\GreaterThan(value="0", message="The count per page should be positive")
     * @Assert\LessThan(value="250", message="The count per page should be less than 250")
     */
    private $perPage = 25;

    /**
     * GetListRequest constructor.
     * @param int $page
     * @param int $perPage
     */
    public function __construct(int $page = 1, int $perPage = 25)
    {
        $this->page = $page;
        $this->perPage = $perPage;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return ($this->page - 1) * $this->perPage;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->perPage;
    }
}
