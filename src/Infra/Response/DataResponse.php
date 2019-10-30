<?php


namespace App\Infra\Response;


class DataResponse extends SuccessResponse
{
    public function __construct($data = null, $wholeCount = null, $status = 200, array $headers = array())
    {
        if ($data == null) {
            $data = [];
        }
        $data = ['data' => $data];
        if ($wholeCount !== null) {
            $data['wholeCount'] = $wholeCount;
        }

        parent::__construct($data, $status, $headers);
    }
}