<?php

namespace App\Example\Repository\Specification\City\Selection;

use App\Example\Entity\City;
use App\Example\Repository\CityRepository;
use Rb\Specification\Doctrine\Query\Select;
use Rb\Specification\Doctrine\Specification;

/**
 * Class SelectBaseData
 * @package App\Example\Repository\Specification\City\Selection
 */
class SelectBaseData extends Specification
{
    /**
     * SelectBaseData constructor.
     * @param bool $addToSelect
     * @param string $dqlAlias
     */
    public function __construct(bool $addToSelect = true, string $dqlAlias = CityRepository::DQL_ALIAS)
    {
        $add = Select::ADD_SELECT;
        if (!$addToSelect) {
            $add = Select::SELECT;
        }
        $specs = [
            new Select('partial ' . $dqlAlias . '.{
                id, 
                name
                }',
                $add),
        ];
        parent::__construct($specs);
    }

    public function isSatisfiedBy($value)
    {
        return $value === City::class;
    }
}
