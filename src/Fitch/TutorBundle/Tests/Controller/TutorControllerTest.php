<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TutorControllerTest extends WebTestCase
{
    use AuthorisedClientTrait;

    public function testAccess()
    {
        $users = [
            '' => 302,
            'xuser' => 200,
            'xdisabled' => 200, // you can switch to a disabled account, even if you cant log in as one
            'xeditor' => 200,
            'xadmin' => 200,
            'xsuper' => 200,
        ];

        $this->checkAccess('GET', '/', $users);
    }

    public function testReadOnly()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xuser');
        $crawler = $client->request('GET', '/');

        // Check Add button isn't present
        $this->assertEquals(0, $crawler->filter('a:contains("Create a new entry")')->count());

        // Check that the url is blocked
        $this->checkAccess('GET', '/new', ['xuser' => 403]);
    }

    public function testCreateNewTutor()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xeditor');

        // Create a new entry in the database
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /user/");

        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'fitch_tutorbundle_tutor[name]' => 'xtest',
            'fitch_tutorbundle_tutor[region]' => 1,
            'fitch_tutorbundle_tutor[status]' => 1,
            'fitch_tutorbundle_tutor[tutorType]' => 1,
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        //echo $crawler->html();
        $this->assertGreaterThan(
            0,
            $crawler->filter('h2:contains("xtest")')->count(),
            'Missing element h2:contains("xtest")'
        );
    }
}
