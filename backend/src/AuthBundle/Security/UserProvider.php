<?php

namespace App\AuthBundle\Security;

use App\AuthBundle\Entity\User;
use App\AuthBundle\Service\UserService;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function refreshUser(UserInterface $user): ?UserInterface
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException('Expected an instance of %s, but got "%s".');
        }

        if (null === $reloadedUser = $this->userService->find($user->getId())) {
            throw new UsernameNotFoundException(sprintf('User with ID "%s" could not be reloaded.', $user->getId()));
        }

        return $reloadedUser;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userService->findUserByToken($identifier);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Email "%s" does not exist.', $identifier));
        }

        return $user;
    }
}
