<?php

namespace App\Documentation\Entity;

use App\Documentation\Entity\Enum\DocumentStatus;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="document")
 * @ORM\Entity()
 */
class Document
{
    /**
     * @var UuidInterface
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;
    
    /**
     * @var DocumentStatus
     * @ORM\Column(type="string", length=55, nullable=false)
     */
    private $status;

    /**
     * @var array
     * @ORM\Column(name="payload", type="json", nullable=false)
     */
    private $payload;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     */
    private $createAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="update")
     */
    private $modifyAt;

    /**
     * Document constructor.
     * @throws \Exception
     */
    public function __construct(array $payload = [])
    {
        $this->id = Uuid::uuid4();
        $this->status = DocumentStatus::draft()->getValue();
        $this->payload = $payload;
    }


    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return DocumentStatus
     */
    public function getStatus(): DocumentStatus
    {
        return new DocumentStatus($this->status);
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @return \DateTime
     */
    public function getCreateAt(): \DateTime
    {
        return $this->createAt;
    }

    /**
     * @return \DateTime
     */
    public function getModifyAt(): \DateTime
    {
        return $this->modifyAt;
    }

    public function publish(): self
    {
        if ($this->status !== DocumentStatus::draft()->getValue()) {
            throw new \Exception('The status can be changed to the published only from the draft status.');
        }
        $this->status = DocumentStatus::published()->getValue();

        return $this;
    }

    /**
     * @param array $payload
     * @return Document
     * @throws \Exception
     */
    public function setPayload(array $payload): Document
    {
        if ($this->status !== DocumentStatus::draft()->getValue()) {
            throw new \Exception('The payload can be changed only for the drafts.');
        }
        $this->payload = $payload;
        return $this;
    }
}

