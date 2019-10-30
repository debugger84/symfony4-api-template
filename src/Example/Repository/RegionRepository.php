<?php

namespace App\Example\Repository;

use App\Example\Entity\Region;
use App\Infra\Repository\BaseRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RegionRepository extends BaseRepository
{
    const DQL_ALIAS = 'region';
    protected $dqlAlias = self::DQL_ALIAS;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Region::class);
    }
}
