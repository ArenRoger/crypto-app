<?php

namespace App\ExchangeRateBundle\Service;

use App\ExchangeRateBundle\Service\Strategy\CryptoStrategy;
use App\ExchangeRateBundle\Type\CryptoRate;

class GetExchangeRateService
{
    private CryptoStrategy $cryptoStrategy;

    public function setStrategy(CryptoStrategy $cryptoStrategy)
    {
        $this->cryptoStrategy = $cryptoStrategy;
    }

    public function getRateToUsd(): CryptoRate
    {
        return $this->cryptoStrategy->getRateToUsd();
    }

    public function getRateFromUsdToOther($currency)
    {
        // TODO Get rate USD to other($currency)
    }
}
