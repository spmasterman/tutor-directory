<?php

namespace Fitch\FrontEndBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StatsControllerTest extends WebTestCase
{
    public function testTop()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/top');
    }
}
