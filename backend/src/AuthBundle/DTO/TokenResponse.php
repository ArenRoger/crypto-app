<?php

namespace App\AuthBundle\DTO;

use App\CommonBundle\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class TokenResponse extends BaseDto
{
    public string $accessToken;

    public string $refreshToken;

    public string $expiresAt;
}
