<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CompetencyLevelControllerTest extends WebTestCase
{
    use AuthorisedClientTrait;

    public function testAccess()
    {
        $users = [
            '' => 302,
            'xuser' => 403,
            'xdisabled' => 403,
            'xeditor' => 403,
            'xadmin' => 200,
            'xsuper' => 200,
        ];

        $this->checkAccess('GET', '/admin/level/competency/', $users);
    }

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xadmin');

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/level/competency/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /user/");
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form([
            'fitch_tutorbundle_competencylevel[name]'  => 'xtest',
            'fitch_tutorbundle_competencylevel[color]' => '#daac8a',
        ]);

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(
            0,
            $crawler->filter('td:contains("xtest")')->count(),
            'Missing element td:contains("Test")'
        );

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form([
            'fitch_tutorbundle_competencylevel[name]'  => 'xtest-edit',
            'fitch_tutorbundle_competencylevel[color]' => '#db8c8b',
        ]);

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('[value="xtest-edit"]')->count(),
            'Missing element [value="xtest-edit"]'
        );

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/xtest-edit/', $client->getResponse()->getContent());
    }
}
