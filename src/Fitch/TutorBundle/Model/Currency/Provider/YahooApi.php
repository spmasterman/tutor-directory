<?php

namespace Fitch\TutorBundle\Model\Currency\Provider;

class YahooApi implements ProviderInterface
{
    /**
     * Url for Curl request.
     *
     * @var string
     */
    const API_URL = 'http://download.finance.yahoo.com/d/quotes.csv?s=[fromCurrency][toCurrency]=X&f=nl1d1t1';

    /**
     * @var HttpRequestInterface $httpRequestInterface
     */
    private $httpRequestInterface;

    public function __construct(HttpRequestInterface $httpRequestInterface)
    {
        $this->httpRequestInterface = $httpRequestInterface;
    }

    /**
     * {@inheritDoc}
     */
    public function getRate($fromCurrency, $toCurrency)
    {
        $fromCurrency = urlencode($fromCurrency);
        $toCurrency = urlencode($toCurrency);

        $url = str_replace(
            ['[fromCurrency]', '[toCurrency]'],
            [$fromCurrency, $toCurrency],
            static::API_URL
        );

        $timeout = 0;

        $this->httpRequestInterface->setOption(CURLOPT_URL, $url);
        $this->httpRequestInterface->setOption(CURLOPT_RETURNTRANSFER, 1);
        $this->httpRequestInterface->setOption(CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)');
        $this->httpRequestInterface->setOption(CURLOPT_CONNECTTIMEOUT, $timeout);

        $rawData = $this->httpRequestInterface->execute();
        $this->httpRequestInterface->close();

        return explode(',', $rawData)[1];
    }
}
