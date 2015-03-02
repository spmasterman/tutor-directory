<?php

use Fitch\TutorBundle\Model\Currency\Provider\HttpRequestInterface;
use Fitch\TutorBundle\Model\Currency\Provider\YahooApi;

class YahooAPITest extends PHPUnit_Framework_TestCase
{
    public function testYahooAPIPassing()
    {
        $api = new YahooApi($this->getHTTPRequestMock());
        $this->assertEquals(
            1.5362,
            $api->getRate('GBP', 'USD')
        )
        ;
    }

    /**
     * @return HttpRequestInterface
     */
    public function getHTTPRequestMock()
    {
        $httpMock = $this->getMockBuilder('Fitch\TutorBundle\Model\Currency\Provider\HttpRequestInterface')->getMock();
        $httpMock->expects($this->once())->method('execute')->willReturn('GBP to USD,1.5362,3/2/2015,4:55pm');
        return $httpMock;
    }
}
