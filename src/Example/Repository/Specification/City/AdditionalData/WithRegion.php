<?php

namespace App\Example\Repository\Specification\City\AdditionalData;

use App\Example\Entity\City;
use App\Example\Repository\CityRepository;
use App\Example\Repository\RegionRepository;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Query\Join;
use Rb\Specification\Doctrine\Specification;

/**
 * Class WithRegion
 * @package App\Example\Repository\Specification\City\AdditionalData
 */
class WithRegion extends Specification
{
    /**
     * WithRegion constructor.
     * @param string $dqlAlias
     * @throws InvalidArgumentException
     */
    public function __construct($dqlAlias = CityRepository::DQL_ALIAS)
    {
        $joinedAlias = RegionRepository::DQL_ALIAS;
        $specs = [
            new Join('region', $joinedAlias, $dqlAlias),
        ];
        parent::__construct($specs);
    }

    public function isSatisfiedBy($value)
    {
        return $value === City::class;
    }
}
