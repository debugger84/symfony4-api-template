<?php

namespace App\Example\Repository\Specification\Region\AdditionalData;

use App\Example\Entity\City;
use App\Example\Entity\Region;
use App\Example\Repository\CityRepository;
use App\Example\Repository\RegionRepository;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Query\Join;
use Rb\Specification\Doctrine\Specification;

/**
 * Class WithCountry
 * @package App\Example\Repository\Specification\Region\AdditionalData
 */
class WithCountry extends Specification
{
    /**
     * WithLevel constructor.
     * @param string $dqlAlias
     * @throws InvalidArgumentException
     */
    public function __construct($dqlAlias = RegionRepository::DQL_ALIAS)
    {
        $joinedAlias = RegionRepository::DQL_ALIAS;
        $specs = [
            new Join('country', $joinedAlias, $dqlAlias),
        ];
        parent::__construct($specs);
    }

    public function isSatisfiedBy($value)
    {
        return $value === Region::class  || $value === City::class;
    }
}
