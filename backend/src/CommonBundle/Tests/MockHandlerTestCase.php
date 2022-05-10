<?php

declare(strict_types=1);

namespace App\CommonBundle\Tests;

use App\CommonBundle\Tests\Fake\ClientFake;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MockHandlerTestCase extends AbstractMangoWebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // TODO Truncate all db
        $this->getEntityManager()->beginTransaction();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->getEntityManager()->close();
    }

    /**
     * @var ClientFake|null
     */
    protected ?ClientFake $clientInterface;

    /** @var KernelBrowser */
    protected KernelBrowser $client;

    /** @var ContainerInterface|null */
    protected ?ContainerInterface $Container;

    protected function initClient()
    {
        $this->client = static::createClient();
        $this->Container = $this->client->getContainer();
        $this->clientInterface = $this->Container->get('psr18.client');
    }

    protected function prepareMock($response)
    {
        $this->clientInterface->appendResponse([$response]);
    }
}
