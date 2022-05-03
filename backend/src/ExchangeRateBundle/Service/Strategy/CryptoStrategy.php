<?php

namespace App\ExchangeRateBundle\Service\Strategy;

use App\ExchangeRateBundle\Type\CryptoRate;

interface CryptoStrategy
{
    public function getRateToUsd(): CryptoRate;
}
