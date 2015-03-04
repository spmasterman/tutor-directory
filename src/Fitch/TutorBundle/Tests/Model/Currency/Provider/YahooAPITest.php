<?php

namespace Fitch\TutorBundle\Model\Currency\Provider;

/**
 * Class YahooAPITest
 */
class YahooAPITest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testYahooAPIPassing()
    {
        $api = new YahooApi($this->getHTTPRequestMock('GBP to USD,1.5362,3/2/2015,4:55pm'));
        $this->assertEquals(
            1.5362,
            $api->getRate('GBP', 'USD')
        );
    }

    /**
     *
     */
    public function testYahooAPIFailing()
    {
        $api = new YahooApi($this->getHTTPRequestMock(false));
        $this->assertFalse(
            $api->getRate('GBP', 'USD')
        );
    }


    /**
     * @param bool|string $return
     * @return HttpRequestInterface
     */
    public function getHTTPRequestMock($return)
    {
        $httpMock = $this->getMockBuilder('Fitch\TutorBundle\Model\Currency\Provider\HttpRequestInterface')->getMock();
        $httpMock->expects($this->once())->method('execute')->willReturn($return);

        return $httpMock;
    }
}
