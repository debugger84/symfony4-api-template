<?php

namespace App\Documentation\Request\Document;

use App\Infra\Request\RequestObject\RequestObjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateDocumentRequest implements RequestObjectInterface
{
    /**
     * @var array
     * @Assert\NotBlank(message="The document field is required")
     */
    private $document;

    /**
     * @var array
     * @Assert\NotNull(message="The field document.payload is required")
     */
    private $payload;

    /**
     * UpdateDocumentRequest constructor.
     * @param array $document
     */
    public function __construct(array $document = [])
    {
        $this->document = $document;
        if (isset($document['payload'])) {
            $this->payload = $document['payload'];
        }
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @Assert\IsTrue(message="The payload should be an array")
     */
    public function isPayloadValid(): bool
    {
        return is_array($this->payload);
    }
}
