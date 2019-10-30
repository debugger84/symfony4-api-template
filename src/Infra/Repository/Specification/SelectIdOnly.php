<?php


namespace App\Infra\Repository\Specification;


use Rb\Specification\Doctrine\Query\Select;
use Rb\Specification\Doctrine\Specification;

/**
 * Class IsSeries
 * Specification pattern to find tv series
 * @see
 * @package App\Repository\Specification\Movie
 */
class SelectIdOnly extends Specification
{
    /**
     * SelectIdOnly constructor.
     * @param string $dqlAlias
     * @param bool $distinct
     */
    public function __construct(string $dqlAlias, bool $distinct = false)
    {
        $specs = [
            new Select((($distinct) ? 'DISTINCT ' : '') .
                $dqlAlias . '.id', Select::SELECT),
        ];
        parent::__construct($specs);
    }

    public function isSatisfiedBy($value)
    {
        return true;
    }
}