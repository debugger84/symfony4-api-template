<?php

namespace App\Documentation\Transformer\Document;

use App\Documentation\Entity\Document;
use League\Fractal\TransformerAbstract;

class BaseDataTransformer extends TransformerAbstract
{
    public function transform(Document $document)
    {
        $data = [
            'id' => $document->getId()->toString(),
            'status' => $document->getStatus()->getValue(),
            'payload' => $document->getPayload(),
            'createAt' => $document->getCreateAt()->format(\DateTime::ISO8601),
            'modifyAt' => $document->getModifyAt()->format(\DateTime::ISO8601),
        ];

        return $data;
    }
}
