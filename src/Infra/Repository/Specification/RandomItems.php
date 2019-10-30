<?php

namespace App\Infra\Repository\Specification;

use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Query\Select;
use Rb\Specification\Doctrine\Specification;


/**
 * Class RandomScreenShot
 * @package App\Repository\Specification\GiftCode
 */
class RandomItems extends Specification
{
    /**
     * RandomScreenShot constructor.
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $specs = [
            new Select('RAND() as HIDDEN rand'),
            new OrderByValue('rand'),
        ];

        parent::__construct($specs);
    }

    public function isSatisfiedBy($value)
    {
        return true;
    }
}
