<?php

namespace App\AuthBundle\DTO;

use App\CommonBundle\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class LoginResponse extends BaseDto
{
    public string $accessToken;

    public string $refreshToken;

    public int $expiresAt;

    public int $userId;
}
