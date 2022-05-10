<?php

namespace App\AuthBundle\Service;

use App\AuthBundle\DTO\LoginResponse;
use App\AuthBundle\Entity\User;
use App\AuthBundle\Repository\UserRepository;

class LoginService
{
    protected UserRepository $userRepository;
    protected TokenService $tokenService;

    public function __construct(
        UserRepository $userRepository,
        TokenService $tokenService,
    ) {
        $this->userRepository = $userRepository;
        $this->tokenService = $tokenService;
    }

    public function login(string $email, string $password): LoginResponse
    {
        $user = $this->userRepository->findOneByEmail($email);
        if ($user === null) {
            throw new \Exception('User not exist');
        }

        if (!$this->checkPassword($user, $password)) {
            throw new \Exception('Incorrect password');
        }

        $this->tokenService->deleteUserTokens($user);

        $token = $this->tokenService->createToken($user)->toArray();

        return new LoginResponse(
            array_merge($token, ['userId' => $user->getId()])
        );
    }

    protected function checkPassword(User $user, string $password): bool
    {
        return password_verify($password, $user->getPassword());
    }
}
