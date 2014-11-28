<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class OperatingRegionControllerTest extends WebTestCase
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
            'xsuper' => 200
        ];

        $this->checkAccess('GET', '/admin/region/', $users);
    }


    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xadmin');

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/region/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /user/");
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'fitch_tutorbundle_operatingregion[name]'  => 'xtest',
            'fitch_tutorbundle_operatingregion[code]'  => 'xt',
            'fitch_tutorbundle_operatingregion[default]'  => false,
            'fitch_tutorbundle_operatingregion[defaultCurrency]' => 1
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("xtest")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'fitch_tutorbundle_operatingregion[name]'  => 'xtest-edit',
            'fitch_tutorbundle_operatingregion[code]'  => 'xte',
            'fitch_tutorbundle_operatingregion[default]'  => true,
            'fitch_tutorbundle_operatingregion[defaultCurrency]' => 2
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertGreaterThan(0, $crawler->filter('[value="xtest-edit"]')->count(), 'Missing element [value="xtest-edit"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/xtest-edit/', $client->getResponse()->getContent());
    }
}
