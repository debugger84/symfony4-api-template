<?php

namespace App\Documentation\Request\Document;

use App\Infra\Request\RequestObject\RequestObjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateDocumentRequest implements RequestObjectInterface
{
    /**
     * @var array
     */
    private $payload = [];

    /**
     * CreateDocumentRequest constructor.
     * @param int $payload
     */
    public function __construct(array $payload = [])
    {
        $this->payload = $payload;
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
