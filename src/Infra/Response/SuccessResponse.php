<?php


namespace App\Infra\Response;


use Symfony\Component\HttpFoundation\JsonResponse;

class SuccessResponse extends JsonResponse
{
    public function __construct($data = null, $status = 200, array $headers = array())
    {
        $statusArray = ['status' => true];
        if (is_array($data)) {
            $data = array_merge($statusArray, $data);
        } else {
            $data = $statusArray;
        }
        $this->encodingOptions = JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_UNICODE;

        parent::__construct($data, $status, $headers, false);
    }

}