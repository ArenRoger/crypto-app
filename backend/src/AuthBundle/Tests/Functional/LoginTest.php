<?php

declare(strict_types=1);

namespace App\AuthBundle\Tests\Functional;

use App\AuthBundle\Entity\AccessToken;
use App\AuthBundle\Entity\RefreshToken;
use App\AuthBundle\Service\TokenService;
use App\CommonBundle\Tests\MockHandlerTestCase;

class LoginTest extends MockHandlerTestCase
{
    protected function setUp(): void
    {
        $this->initClient();
        parent::setUp();
    }

    public function testLogin(): void
    {
        $body = parent::TEST_USER;

        $testUser = $this->createTestUser();

        $this->client->request(
            'POST',
            '/api/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($body, JSON_THROW_ON_ERROR)
        );
        $responseData = $this->getJsonResponseData($this->client);

        self::assertArrayHasKey('accessToken', $responseData);
        self::assertArrayHasKey('refreshToken', $responseData);
        self::assertArrayHasKey('expiresAt', $responseData);
        self::assertArrayHasKey('userId', $responseData);

        self::assertSame($responseData['userId'], $testUser->getId());

        /** @var TokenService */
        $tokenService = $this->Container->get(TokenService::class);

        /** @var AccessToken[] */
        $allAccessTokens = $tokenService->getAllAccessTokens();

        self::assertSame($allAccessTokens[0]->getToken(), $responseData['accessToken']);
        self::assertSame($allAccessTokens[0]->getExpiresAt(), $responseData['expiresAt']);
        self::assertSame($allAccessTokens[0]->getUser()->getId(), $responseData['userId']);

        /** @var RefreshToken[] */
        $allRefreshTokens = $tokenService->getAllRefreshTokens();

        self::assertSame($allRefreshTokens[0]->getToken(), $responseData['refreshToken']);
        self::assertSame($allRefreshTokens[0]->getUser()->getId(), $responseData['userId']);
    }

    public function testLoginWithEmptyCredentials()
    {
        $emptyUserEmailCredentials = [
            'password' => 'somepassword',
        ];

        $emptyUserPasswordCredentials = [
            'email' => 'someemail@gmail.com',
        ];

        $testUser = $this->createTestUser();

        $this->client->request(
            'POST',
            '/api/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($emptyUserEmailCredentials, JSON_THROW_ON_ERROR)
        );
        $responseData = $this->getJsonResponseData($this->client);

        self::assertSame($responseData['email'][0], 'The email field cannot be empty');

        $this->client->request(
            'POST',
            '/api/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($emptyUserPasswordCredentials, JSON_THROW_ON_ERROR)
        );
        $responseData = $this->getJsonResponseData($this->client);

        self::assertSame($responseData['password'][0], 'The password field cannot be empty');
    }

    public function testLoginBadCredentials()
    {
        $incorrectUserCredentials = [
            'email' => 'nonexist@gmail.com',
            'password' => 'somepassword',
        ];

        $testUser = $this->createTestUser();

        $this->client->request(
            'POST',
            '/api/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($incorrectUserCredentials, JSON_THROW_ON_ERROR)
        );
        $responseData = $this->getJsonResponseData($this->client);

        self::assertSame($responseData, 'Bad credentials');
    }
}
