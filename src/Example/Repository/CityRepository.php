<?php

namespace App\Example\Repository;

use App\Example\Entity\City;
use App\Infra\Repository\BaseRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CityRepository extends BaseRepository
{
    const DQL_ALIAS = 'city';
    protected $dqlAlias = self::DQL_ALIAS;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, City::class);
    }
}
