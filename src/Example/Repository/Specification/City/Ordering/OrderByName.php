<?php

namespace App\Example\Repository\Specification\City\Ordering;

use App\Example\Entity\City;
use App\Example\Repository\CityRepository;
use Rb\Specification\Doctrine\Query\OrderBy;
use Rb\Specification\Doctrine\Specification;

/**
 * Class OrderByName
 * @package App\Repository\Specification\Gift
 */
class OrderByName extends Specification
{
    /**
     * LastConfirmed constructor.
     * @throws \Rb\Specification\Doctrine\Exception\InvalidArgumentException
     */
    public function __construct()
    {
        $specs = [
            new OrderBy( 'name', 'ASC', CityRepository::DQL_ALIAS)
        ];

        parent::__construct($specs);
    }

    public function isSatisfiedBy($value)
    {
        return $value === City::class;
    }
}