<?php

namespace App\CommonBundle\DTO;

use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validation;

interface BaseDtoInterface
{
    public function toArray(): array;

    public function getProperties(): array;
}
