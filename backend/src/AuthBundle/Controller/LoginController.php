<?php

namespace App\AuthBundle\Controller;

use App\AuthBundle\DTO\UserLoginDto;
use App\AuthBundle\Service\LoginService;
use App\AuthBundle\Service\UserService;
use App\CommonBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Security;

class LoginController extends BaseController
{
    protected UserService $userService;
    protected LoginService $loginService;

    public function __construct(
        ValidatorInterface $validator,
        UserService $userService,
        LoginService $loginService
    ) {
        parent::__construct($validator);

        $this->userService = $userService;
        $this->loginService = $loginService;
    }

    #[OA\Parameter(
        name: 'email',
        description: 'User email',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'password',
        description: 'User password',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Return Token and User ID',
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Validation error',
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Bad credentials',
    )]
    #[OA\Tag(name: 'Auth')]
    #[Route(path: '/login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $userLoginDto = new UserLoginDto(json_decode($request->getContent(), true));
        $errors = $this->validateDto($userLoginDto);
        if (count($errors) > 0) {
            return $this->json($errors,  Response::HTTP_BAD_REQUEST);
        }

        try {
            $login = $this->loginService->login($userLoginDto->email, $userLoginDto->password);
        } catch (\Exception $e) {
            return $this->json('Bad credentials',  Response::HTTP_BAD_REQUEST);
        }

        return $this->json($login->toArray());
    }
}
