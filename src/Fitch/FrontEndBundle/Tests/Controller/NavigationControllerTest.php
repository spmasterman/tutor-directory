<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class NavigationControllerTest.
 */
class NavigationControllerTest extends WebTestCase
{
    use AuthorisedClientTrait;

    /**
     *
     */
    public function testSidebar()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xadmin');

        // get rendering of sidebar
        $crawler = $client->request('GET', '/sidebar');

        $this->assertGreaterThan(
            0,
            $crawler->filter('div.left-sidebar')->count()
        );
        $wasOpen = (bool) ($crawler->filter('div.left-sidebar.minified')->count() == 0);

        // toggle it
        $client->request('GET', '/toggle/sidebar');

        // get rendering of sidebar again
        $crawler = $client->request('GET', '/sidebar');
        $isOpenNow = (bool) ($crawler->filter('div.left-sidebar.minified')->count() == 0);

        $this->assertNotEquals($wasOpen, $isOpenNow);
    }

    /**
     *
     */
    public function testSidebarForUnprivilegedUser()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xuser');

        // get rendering of sidebar
        $crawler = $client->request('GET', '/sidebar');

        $this->assertEquals(
            0,
            $crawler->filter('div.left-sidebar')->count()
        );
    }
}
