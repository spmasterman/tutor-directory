<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class FileTypeControllerTest extends WebTestCase
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

        $this->checkAccess('GET', '/admin/type/file/', $users);
    }


    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xadmin');

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/type/file/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /user/");
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'fitch_tutorbundle_filetype[name]'  => 'xtest',
            'fitch_tutorbundle_filetype[private]'  => false,
            'fitch_tutorbundle_filetype[default]'  => false,
            'fitch_tutorbundle_filetype[suitableForProfilePicture]'  => false,
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("xtest")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'fitch_tutorbundle_filetype[name]'  => 'xtest-edit',
            'fitch_tutorbundle_filetype[private]'  => true,
            'fitch_tutorbundle_filetype[default]'  => true,
            'fitch_tutorbundle_filetype[suitableForProfilePicture]'  => true,
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
