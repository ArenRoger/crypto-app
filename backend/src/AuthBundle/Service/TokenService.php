<?php

namespace App\AuthBundle\Service;

use App\AuthBundle\DTO\TokenResponse;
use App\AuthBundle\Entity\AccessToken;
use App\AuthBundle\Entity\User;
use App\AuthBundle\Repository\AccessTokenRepository;
use App\AuthBundle\Repository\RefreshTokenRepository;
use DateInterval;

class TokenService
{
    protected AccessTokenRepository $accessTokenRepository;
    protected RefreshTokenRepository $refreshTokenRepository;

    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        RefreshTokenRepository $refreshTokenRepository
    ) {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->refreshTokenRepository = $refreshTokenRepository;
    }

    public function createToken(User $user): TokenResponse
    {
        $accessToken = $this->accessTokenRepository->create($this->generateToken(), $user);
        $refreshToken = $this->refreshTokenRepository->create($this->generateToken(), $user);

        return new TokenResponse([
            'accessToken' => $accessToken->getToken(),
            'refreshToken' => $refreshToken->getToken(),
            'expiresAt' => $accessToken->getExpiresAt(),
        ]);
    }

    protected function generateToken(): string
    {
        return bin2hex(random_bytes(125));
    }

    public function getAllAccessTokens(): array
    {
        return $this->accessTokenRepository->findAll();
    }

    public function getAllRefreshTokens(): array
    {
        return $this->refreshTokenRepository->findAll();
    }

    public function deleteUserTokens(User $user): void
    {
        $this->accessTokenRepository->deleteByUserId($user->getId());
        $this->refreshTokenRepository->deleteByUserId($user->getId());
    }

    public function findAccessToken(string $accessToken): ?AccessToken
    {
        return $this->accessTokenRepository->findOneBy([
            'token' => $accessToken
        ]);
    }

    public function isAccessTokenExpired(string $accessToken): bool {
        $accessToken = $this->findAccessToken($accessToken);

        $expiresDateTime = $accessToken->getCreatedAt()->add(new DateInterval('PT' . $accessToken->getExpiresAt() . 'S'));
        $now = new \DateTime('now');
        if ($now > $expiresDateTime) {
            return true;
        }

        return false;
    }
}
