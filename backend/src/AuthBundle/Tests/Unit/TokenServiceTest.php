<?php

declare(strict_types=1);

namespace App\AuthBundle\Tests\Unit;

use App\AuthBundle\Entity\AccessToken;
use App\AuthBundle\Entity\RefreshToken;
use App\AuthBundle\Service\TokenService;
use App\CommonBundle\Tests\MockHandlerTestCase;

class TokenServiceTest extends MockHandlerTestCase
{
    protected function setUp(): void
    {
        $this->initClient();
        parent::setUp();
    }

    public function testCreateToken(): void
    {
        $testUser = $this->createTestUser();

        /** @var TokenService */
        $tokenService = $this->Container->get(TokenService::class);

        $tokenService->createToken($testUser);

        /** @var AccessToken[] */
        $allAccessTokens = $tokenService->getAllAccessTokens();
        /** @var RefreshToken[] */
        $allRefreshTokens = $tokenService->getAllRefreshTokens();

        self::assertObjectHasAttribute('token', $allAccessTokens[0]);
        self::assertObjectHasAttribute('user', $allAccessTokens[0]);
        self::assertObjectHasAttribute('expiresAt', $allAccessTokens[0]);

        self::assertObjectHasAttribute('token', $allRefreshTokens[0]);
        self::assertObjectHasAttribute('user', $allRefreshTokens[0]);
        self::assertObjectHasAttribute('expiresAt', $allRefreshTokens[0]);

        self::assertSame($allAccessTokens[0]->getUser()->getId(), $testUser->getId());
        self::assertSame($allRefreshTokens[0]->getUser()->getId(), $testUser->getId());
    }

    public function testDeleteUserTokens(): void
    {
        $testUser = $this->createTestUser();

        /** @var TokenService */
        $tokenService = $this->Container->get(TokenService::class);

        $tokenService->createToken($testUser);
        $tokenService->createToken($testUser);
        $tokenService->createToken($testUser);

        $tokenService->deleteUserTokens($testUser);

        /** @var AccessToken[] */
        $allAccessTokens = $tokenService->getAllAccessTokens();
        /** @var RefreshToken[] */
        $allRefreshTokens = $tokenService->getAllRefreshTokens();

        self::assertCount(0, $allAccessTokens);
        self::assertCount(0, $allRefreshTokens);
    }
}
