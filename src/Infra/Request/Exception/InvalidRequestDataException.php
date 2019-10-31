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
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
