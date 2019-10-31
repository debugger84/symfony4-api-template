<?php

namespace App\Documentation\Repository\Specification\Document\Ordering;

use App\Documentation\Entity\Document;
use App\Documentation\Repository\DocumentRepository;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Query\OrderBy;
use Rb\Specification\Doctrine\Specification;

/**
 * Class NewerFirst
 * @package App\Documentation\Repository\Specification\Document\Ordering
 */
class NewerFirst extends Specification
{
    /**
     * LastConfirmed constructor.
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $specs = [
            new OrderBy( 'createAt', 'DESC', DocumentRepository::DQL_ALIAS)
        ];

        parent::__construct($specs);
    }

    public function isSatisfiedBy($value)
    {
        return $value === Document::class;
    }
}
