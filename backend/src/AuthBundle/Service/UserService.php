<?php

namespace App\AuthBundle\Service;

use App\AuthBundle\Entity\User;
use App\AuthBundle\Repository\AccessTokenRepository;
use App\AuthBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    protected UserRepository $userRepository;
    protected TokenService $tokenService;

    public function __construct(
        UserRepository $userRepository,
        TokenService $tokenService,
    )
    {
        $this->userRepository = $userRepository;
        $this->tokenService = $tokenService;
    }

    public function isExist(string $email): bool
    {
        $user = $this->userRepository->findOneBy([
            'email' => $email
        ]);

        if ($user) {
            return true;
        }

        return false;
    }

    public function create(string $email, string $password): User
    {
        return $this->userRepository->create($email, $password);
    }

    public function find(int $id): ?UserInterface
    {
        return $this->userRepository->find($id);
    }

    public function findUser(string $email): ?User
    {
        return $this->userRepository->findOneByEmail($email);
    }

    public function findUserByToken(string $accessToken): ?User
    {
        if (null === $accessToken = $this->tokenService->findAccessToken($accessToken)) {
            throw new TokenNotFoundException('Token not found');
        }

        return $accessToken->getUser();
    }
}
