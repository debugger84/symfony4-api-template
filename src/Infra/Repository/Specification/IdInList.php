<?php


namespace App\Infra\Repository\Specification;


use App\Entity\Movie;
use Rb\Specification\Doctrine\Condition\GreaterThan;
use Rb\Specification\Doctrine\Condition\In;
use Rb\Specification\Doctrine\Condition\Like;
use Rb\Specification\Doctrine\Query\OrderBy;
use Rb\Specification\Doctrine\Query\Select;
use Rb\Specification\Doctrine\Specification;

/**
 * Class IdInList
 * @package App\Repository\Specification
 */
class IdInList extends Specification
{
    /**
     * IdInList constructor.
     * @param array $ids
     * @param string $dqlAlias
     */
    public function __construct(array $ids, string $dqlAlias)
    {
        $specs = [
            new In('id', $ids, $dqlAlias),
        ];
        parent::__construct($specs);
    }

    public function isSatisfiedBy($value)
    {
        return true;
    }
}
