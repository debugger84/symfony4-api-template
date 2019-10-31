<?php

namespace App\Infra\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorResponse extends JsonResponse
{
    public function __construct(?string $message = null, int $status = 400, array $headers = array(), array $validationErrors = [])
    {
        $data = [
            'status' => false,
            'error' => $message,
        ];

        if ($validationErrors) {
            $data['errors'] = $validationErrors;
        }

        $this->encodingOptions = JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_UNICODE;

        parent::__construct($data, $status, $headers, false);
    }

}
