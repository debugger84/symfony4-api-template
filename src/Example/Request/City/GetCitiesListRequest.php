<?php

namespace App\Example\Request\City;

use App\Infra\Request\RequestObject\RequestObjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

class GetCitiesListRequest implements RequestObjectInterface
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
    private $countPerPage = 25;

    /**
     * @var string|null
     * @Assert\Regex(pattern="/^[a-zA-Z\-']*$/i", message="The name should contain letters only")
     */
    private $name;

    /**
     * GetCitiesListRequest constructor.
     * @param int $page
     * @param int $countPerPage
     * @param string|null $name
     */
    public function __construct(int $page = 1, int $countPerPage = 25, ?string $name = null)
    {
        $this->page = $page;
        $this->countPerPage = $countPerPage;
        $this->name = $name;
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
    public function getCountPerPage(): int
    {
        return $this->countPerPage;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return ($this->page - 1) * $this->countPerPage;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->countPerPage;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


}