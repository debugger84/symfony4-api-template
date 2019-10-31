<?php

namespace App\Documentation\Repository;

use App\Documentation\Entity\Document;
use App\Infra\Repository\BaseRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class DocumentRepository extends BaseRepository
{
    const DQL_ALIAS = 'document';
    protected $dqlAlias = self::DQL_ALIAS;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Document::class);
    }
}
