<?php

namespace App\CommonBundle\Presenter;

use Symfony\Component\HttpFoundation\JsonResponse;

class Respond
{
    protected mixed $data = null;

    public function __construct(mixed $data = null)
    {
        $this->data = $data;
    }

    public function json(): JsonResponse
    {
        return new JsonResponse($this->data);
    }
}
