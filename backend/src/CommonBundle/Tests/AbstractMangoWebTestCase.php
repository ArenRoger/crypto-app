<?php

declare(strict_types=1);

namespace App\CommonBundle\Tests;

use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractMangoWebTestCase extends WebTestCase
{
    public const BEARER = 'BmU2ZGYzYjE1ZDhmZmU0YzIwMWZjOTRkN2NkYjMzZmX0MDV4NDY0NjgwTWQrZDdkODQ1MzljMGNlYWY4ZWE0ZO';
    public const BEARER_ADMIN = 'udminGYzYjE1ZDhmZmU0YzIwMWZjOTRkN2NkYjMzZmI0MDQ4NDY0NjuwZWQwZDdkOBQ1MzljMGNlYWY4ZWE1RE';

    public const  AUTH_HEADER = [
        'HTTP_AUTHORIZATION' => 'Bearer ' . self::BEARER,
    ];
    public const  AUTH_ADMIN_HEADER = [
        'HTTP_AUTHORIZATION' => 'Bearer ' . self::BEARER_ADMIN,
    ];
    public const  USER_ID = 24;
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
