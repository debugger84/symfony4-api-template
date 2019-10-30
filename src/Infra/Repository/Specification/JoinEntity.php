<?php


namespace App\Infra\Repository\Specification;


use Rb\Specification\Doctrine\Query\Join;

class JoinEntity extends Join
{
    protected function createAliasedName($value, $dqlAlias)
    {
        return $value;
    }

}