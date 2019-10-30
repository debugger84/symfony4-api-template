<?php

namespace App\Infra\Repository\Specification;

use Rb\Specification\Doctrine\Condition\GreaterThan;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Doctrine\ORM\Query\Expr\Comparison as DoctrineComparison;
use Doctrine\ORM\QueryBuilder;

class GreaterThanExpression extends GreaterThan
{
    /**
     * Return a string expression which can be used as condition (in WHERE-clause).
     *
     * @param QueryBuilder $queryBuilder
     * @param string       $dqlAlias
     *
     * @return string
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        return (string) new DoctrineComparison(
            $this->createPropertyWithAlias($dqlAlias),
            $this->operator,
            $this->value
        );
    }
}
