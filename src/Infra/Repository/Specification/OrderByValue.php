<?php

namespace App\Infra\Repository\Specification;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\AbstractSpecification;
use Rb\Specification\Doctrine\Query\OrderBy;

class OrderByValue extends OrderBy
{
    protected function createAliasedName($value, $dqlAlias)
    {
        return $value;
    }
}
