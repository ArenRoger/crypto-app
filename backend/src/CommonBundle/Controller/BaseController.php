<?php

namespace App\CommonBundle\Controller;

use App\CommonBundle\DTO\BaseDtoInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseController extends AbstractController
{
    protected ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getRequest(): Request
    {
        return $this->container->get('request_stack')->getCurrentRequest();
    }

    public function validateDto(BaseDtoInterface $class): array
    {
        $violationsList = $this->validator->validate($class);
        $output = [];

        if ($violationsList->count() > 0) {
            foreach ($violationsList as $violation) {
                $output[$violation->getPropertyPath()][] = $violation->getMessage();
            }
        }

        return $output;
    }
}
