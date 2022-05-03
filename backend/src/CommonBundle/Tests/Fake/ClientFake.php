<?php

declare(strict_types=1);

namespace App\CommonBundle\Tests\Fake;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

/**
 * Class ClientFake
 */
class ClientFake extends Client
{
    /** @var MockHandler */
    protected MockHandler $mockHandler;

    /**
     * ClientFake constructor.
     */
    public function __construct()
    {
        $this->mockHandler = new MockHandler();
        $handler = HandlerStack::create($this->mockHandler);
        parent::__construct(['handler' => $handler]);
    }

    /**
     * @param $responses
     */
    public function appendResponse($responses): void
    {
        $this->mockHandler->append(...$responses);
    }
}
