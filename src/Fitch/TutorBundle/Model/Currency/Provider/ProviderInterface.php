<?php

namespace Fitch\TutorBundle\Model\Currency\Provider;

interface ProviderInterface
{
    /**
     * Gets exchange rate
     *
     * @param  string $fromCurrency
     * @param  string $toCurrency
     * @return float
     */
    public function getRate($fromCurrency, $toCurrency);
}
