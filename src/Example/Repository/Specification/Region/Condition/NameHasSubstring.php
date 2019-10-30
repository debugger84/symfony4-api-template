<?php

namespace App\Example\Repository\Specification\Region\Condition;

use App\Example\Entity\City;
use App\Example\Entity\Region;
use App\Example\Repository\CityRepository;
use App\Example\Repository\RegionRepository;
use Rb\Specification\Doctrine\Condition\Like;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Specification;

/**
 * Class LevelHasNumber
 * @package App\Repository\Specification\Quiz\Level
 */
class NameHasSubstring extends Specification
{
    /**
     * NameHasSubstring constructor.
     * @param string $substring
     * @param string $dqlAlias
     * @throws InvalidArgumentException
     */
    public function __construct(string $substring, $dqlAlias = RegionRepository::DQL_ALIAS)
    {
        $specs = [
            new Like('name', $substring, $dqlAlias),
        ];
        parent::__construct($specs);
    }

    public function isSatisfiedBy($value)
    {
        return $value === Region::class || $value === City::class;
    }
}
