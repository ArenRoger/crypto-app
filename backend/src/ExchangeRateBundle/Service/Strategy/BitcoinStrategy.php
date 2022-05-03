<?php

namespace App\ExchangeRateBundle\Service\Strategy;

use App\ExchangeRateBundle\Exception\BitcoinApiCallErrorException;
use App\ExchangeRateBundle\Type\CryptoRate;
use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

class BitcoinStrategy implements CryptoStrategy
{
    protected ClientInterface $httpClient;
    protected LoggerInterface $logger;

    public function __construct(ClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    /**
     * @throws BitcoinApiCallErrorException
     */
    public function getRateToUsd(): CryptoRate
    {
        try {
            $response = $this->getResponse();

            $rate = $response['bpi']['USD']['rate_float'];

            return new CryptoRate('btc', 'usd', $rate);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            throw new BitcoinApiCallErrorException($e->getMessage());
        }
    }

    public function getResponse(): array
    {
        $url = $this->getUrl();

        $response = $this->httpClient->get($url);

        return json_decode((string) $response->getBody(), true);
    }

    protected function getUrl(): string
    {
        return "https://api.coindesk.com/v1/bpi/currentprice.json";
    }
}
