<?php

namespace App\CommonBundle\Exception;

class ValidateRequestException extends RestException
{
    public const HTTP_CODE = 482;

    protected $message = 'Request validation error';

    protected ?string $field = null;
    protected ?array $params = [];

    public function __construct($message = null, $code = 0, ?string $field = null, ?array $params = [])
    {
        $this->field = $field;
        $this->params = $params;
        parent::__construct($message, $code);
    }

    public function getFiled(): ?string
    {
        return $this->field;
    }

    /**
     * @return array|null
     */
    public function getParams(): ?array
    {
        return $this->params;
    }
}
