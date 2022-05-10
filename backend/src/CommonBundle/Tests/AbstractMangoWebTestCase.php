<?php

declare(strict_types=1);

namespace App\CommonBundle\Tests;

use App\AuthBundle\Entity\User;
use App\AuthBundle\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractMangoWebTestCase extends WebTestCase
{
    public const TEST_USER = [
        'email' => 'arenroger@gmail.com',
        'password' => 'test-password'
    ];

    public const BEARER = '72d36f7284d118c8b68f6f4c172632091e25e46786c0fc5ebc807e2442df97718b9da6b8286c7a164261ca24faba8b5886bcea9f3d29558ed1fa2979e20462b507cd4d5b265cca9201eccc8a0ac6b1445b65d0f31463d06db0578bb2c4160564535c7ca9723582259ea4b0635914674ae60669a80b88df00b0ec0b540893865f3c37d5409a45fb3a245e8063b6fef06e684a328af16f955dd5e6ba159f4f29fdd049bed43d3b0b7aa7b7cef204724d4e4ec3701e2c318abd14b6fb026a4f0080c63a19e6d88bcc087458c13488640224c6bca64e38a0b378e5a39d5b52cc52c615381cad82d1b2f54f38c531f56bba33ce6ac68c3e70f9860918ecb15bcdc6';
    public const BEARER_ADMIN = '1d50a9ea2ef026f72567fcc051c7ed8e232015637170fa1cdd2754d531b572fc9309c542bfbf85cb6d0ff35638b14d9e2755375ebd5c493ae0872deb495ce11941591e2df99b18a40764a0c7c515dd9b0164dd707e4a22ad6cebc0f6187ffebdb233e1d84b629af2002d04f4728042efb4035720abd52492c0c01fee5461453c5913e5ce83a224ae5ed6f177f95bbc58818709a251f9c0f01b4c87d2ebb00ae845cde6741e0d6376e5941865b9d5f1e72fdceb1362c82ec53e545b6d5166b01204a55d8a7bc6ad1d283de87644bfd3416180a2d24125d1c123c7e51bdd3aedb4f4c7a992297f1dc6665bb4811c6090560689cd516a477a7488d1ebe0bc20d2';

    public const  AUTH_HEADER = [
        'HTTP_AUTHORIZATION' => 'Bearer ' . self::BEARER,
    ];
    public const  AUTH_ADMIN_HEADER = [
        'HTTP_AUTHORIZATION' => 'Bearer ' . self::BEARER_ADMIN,
    ];
    public const  USER_ID = 1;
    public const  ADMIN_USER_ID = 25;

    public function getJsonResponseData($client)
    {
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        try {
            return json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $this->assertTrue(false, 'Response must be valid json');
            return null;
        }
    }

    public function createTestUser(): User {
        $userService = $this->Container
            ->get(UserService::class);

        $user = $userService->create(self::TEST_USER['email'], self::TEST_USER['password']);

        return $user;
    }

    /**
     * @return EntityManagerInterface|object
     */
    public function getEntityManager(): ?EntityManagerInterface
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @throws \JsonException
     */
    public function sendRequest(string $uri, array $content, bool $user_admin, string $method = "GET"): KernelBrowser
    {
        if ($user_admin) {
            $header = self::AUTH_ADMIN_HEADER;
        } else {
            $header = self::AUTH_HEADER;
        }
        $client = static::createClient([], $header);
        $client->request(
            $method,
            $uri,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content, JSON_THROW_ON_ERROR)
        );
        return $client;
    }
}
