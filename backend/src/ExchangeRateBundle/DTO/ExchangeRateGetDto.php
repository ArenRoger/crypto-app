<?php

namespace App\ExchangeRateBundle\DTO;

use App\CommonBundle\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class ExchangeRateGetDto extends BaseDto
{
    /**
     * @var string
     * @Assert\NotBlank(message="The from field cannot be empty");
     * @Assert\Type(type="string", message="The from field must be a string")
     */
    public string $from;
}
