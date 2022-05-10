<?php

namespace App\CommonBundle\Service;

use App\CommonBundle\Exception\ValidateRequestException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{

    public ValidatorInterface $validator;

    public function __construct(
        ValidatorInterface $validator
    ) {
        $this->validator = $validator;
    }

    /**
     * @throws ValidateRequestException
     */
    public function validateRequest($request, ?array $groups = null): void
    {
        $errors = $this->validator->validate($request, null, $groups);
        if (count($errors) > 0) {
            $firstError = $errors->get(0);
            throw new ValidateRequestException(
                $firstError->getMessageTemplate(),
                ValidateRequestException::HTTP_CODE,
                $firstError->getPropertyPath(),
                $firstError->getParameters()
            );
        }
    }
}
