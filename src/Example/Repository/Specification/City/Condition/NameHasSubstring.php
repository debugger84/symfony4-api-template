<?php

namespace App\Example\Repository\Specification\City\Condition;

use App\Example\Entity\City;
use App\Example\Repository\CityRepository;
use Rb\Specification\Doctrine\Condition\Like;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Query\Select;
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
    public function __construct(string $substring, $dqlAlias = CityRepository::DQL_ALIAS)
    {
        $specs = [
            new Like('name', $substring, Like::CONTAINS, $dqlAlias),
        ];
        parent::__construct($specs);
    }

    public function isSatisfiedBy($value)
    {
        return $value === City::class;
    }
}
