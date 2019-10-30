<?php

namespace App\Example\Repository;

use App\Example\Entity\Country;
use App\Infra\Repository\BaseRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CountryRepository extends BaseRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Country::class);
    }
}
