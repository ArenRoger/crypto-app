<?php

declare(strict_types=1);

namespace App\ExchangeRateBundle\Tests\Functional;

use App\CommonBundle\Tests\MockHandlerTestCase;
use GuzzleHttp\Psr7\Response as ClientResponse;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRateTest extends MockHandlerTestCase
{
    protected function setUp(): void
    {
        $this->initClient();
        parent::setUp();
    }

    public function testBitcoinStrategy(): void
    {
        $cryptoApiResponse200 = new ClientResponse(Response::HTTP_OK, [], json_encode(
            [
                'bpi' => [
                    'USD' => [
                        'rate_float' => 38401.0611
                    ]
                ]
            ]
        ));

        $this->prepareMock($cryptoApiResponse200);

        $this->client->request(
            'GET',
            '/api/exchange-rate/get',
            ['from' => 'BTC'],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ''
        );
        $responseData = $this->getJsonResponseData($this->client);

        self::assertSame($responseData, 38401.0611);
    }
}
