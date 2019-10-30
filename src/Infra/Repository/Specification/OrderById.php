<?php

namespace App\Infra\Repository\Specification;

use Rb\Specification\Doctrine\Query\OrderBy;
use Rb\Specification\Doctrine\Specification;

/**
 * Class OrderById
 * @package App\Repository\Specification
 */
class OrderById extends Specification
{
    /**
     * OrderById constructor.
     * @param string $dqlAlias
     * @throws \Rb\Specification\Doctrine\Exception\InvalidArgumentException
     */
    public function __construct(string $dqlAlias)
    {
        $specs = [
            new OrderBy('id', 'ASC', $dqlAlias),
        ];
        parent::__construct($specs);
    }

    public function isSatisfiedBy($value)
    {
        return true;
    }
}