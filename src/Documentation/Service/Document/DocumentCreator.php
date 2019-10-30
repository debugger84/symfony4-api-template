<?php

namespace App\Documentation\Service\Document;

use App\Documentation\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class DocumentCreator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * DocumentCreator constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param array $payload
     * @return Document
     * @throws Exception
     */
    public function createDocument(array $payload): Document
    {
        $document = new Document($payload);
        $this->em->persist($document);
        $this->em->flush();

        return $document;
    }
}
