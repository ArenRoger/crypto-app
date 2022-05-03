<?php

namespace App\CommonBundle\Exception;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class for returning Rest compatible errors
 */
class RestException extends Exception
{
    // Default response code in case the exception could not be handled by type
    public const HTTP_CODE = 500;

    protected array $data = [];

    public function __construct($message = null, $code = 0)
    {
        parent::__construct(
            $message ?? $this->getMessage(),
            $code === 0 ? static::HTTP_CODE : $code
        );
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }
}
