<?php

namespace App\Documentation\Service\Document;

use App\Documentation\Entity\Document;
use App\Documentation\Entity\Enum\DocumentStatus;
use App\Documentation\Service\Document\Exception\DocumentException;
use Doctrine\ORM\EntityManagerInterface;

class DocumentUpdater
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
     * @param Document $document
     * @return Document
     * @throws DocumentException
     */
    public function updatePayload(array $payload, Document $document): Document
    {
        if (!$document->getStatus()->equals(DocumentStatus::draft())) {
            throw new DocumentException('You can change payload for the draft only');
        }
        $newPayload = $this->mergePayload($document->getPayload(), $payload);

        $document->setPayload($newPayload);

        $this->em->flush();

        return $document;
    }

    /**
     * @param Document $document
     * @return Document
     * @throws \Exception
     */
    public function publish(Document $document): Document
    {
        if ($document->getStatus()->equals(DocumentStatus::published())) {
            return $document;
        }
        $document->publish();

        $this->em->flush();

        return $document;
    }

    private function mergePayload(array $oldPayload, array $newPayload): array
    {
        $result = array_replace_recursive($oldPayload, $newPayload);
        $this->removeNullValues($result);

        return $result;
    }

    /**
     * @param array $array
     * @return array
     */
    private function removeNullValues(array &$array): array
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = $this->removeNullValues($value);
            }
            if ($value === null) {
                unset($array[$key]);
            }
        }
        return $array;
    }
}
