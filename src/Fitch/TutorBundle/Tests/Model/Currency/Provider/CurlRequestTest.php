<?php

namespace Fitch\TutorBundle\Model\Currency\Provider;

/**
 * Class CurlRequestTest
 */
class CurlRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testCurlReuest()
    {
        $curl = new CurlRequest('NOTAVALIDURL');
        $curl->setOption(CURLOPT_RETURNTRANSFER, 1);
        $this->assertFalse($curl->execute());
        $this->assertEquals('HTTP://NOTAVALIDURL/', $curl->getInfo(CURLINFO_EFFECTIVE_URL));
        $curl->close();
    }
}
