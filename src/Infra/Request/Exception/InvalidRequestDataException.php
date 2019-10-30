<?php


namespace App\Infra\Request\Exception;


class InvalidRequestDataException extends \Exception
{
    /**
     * @var array|iterable
     */
    private $data = [];

    /**
     * InvalidRequestDataException constructor.
     * @param $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;

        reset($data);
        $message = current($data);

        parent::__construct($message);
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
