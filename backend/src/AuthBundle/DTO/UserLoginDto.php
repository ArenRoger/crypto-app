<?php

namespace App\AuthBundle\DTO;

use App\CommonBundle\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class UserLoginDto extends BaseDto
{
    #[Assert\NotBlank(message: 'The email field cannot be empty')]
    #[Assert\Type(type: 'string', message: 'The email field must be a string')]
    #[Assert\Length(max: 255, maxMessage: 'Max length of field email is 255')]
    public string $email;

    #[Assert\NotBlank(message: 'The password field cannot be empty')]
    #[Assert\Type(type: 'string', message: 'The password field must be a string')]
    #[Assert\Length(max: 255, maxMessage: 'Max length of field password is 255')]
    public string $password;
}
