<?php

namespace App\ExchangeRateBundle\Type;

class CryptoRate
{
    public string $crypto;
    public string $to;
    public mixed $rate;

    public function __construct(string $crypto, string $to, mixed $rate)
    {
        $this->crypto = $crypto;
        $this->to = $to;
        $this->rate = $rate;
    }
}
