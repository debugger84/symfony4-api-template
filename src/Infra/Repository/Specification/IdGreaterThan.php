<?php

namespace App\Infra\Repository\Specification;

use Rb\Specification\Doctrine\Condition\GreaterThan;
use Rb\Specification\Doctrine\Specification;

/**
 * Class IdGreaterThan
 * @package App\Repository\Specification
 */
class IdGreaterThan extends Specification
{
    /**
     * IdGreaterThan constructor.
     * @param int $value
     * @param string $dqlAlias
     * @throws \Rb\Specification\Doctrine\Exception\InvalidArgumentException
     */
    public function __construct(int $value, string $dqlAlias)
    {
        $specs = [
            new GreaterThan('id', $value, $dqlAlias),
        ];
        parent::__construct($specs);
    }

    public function isSatisfiedBy($value)
    {
        return true;
    }
}