<?php

namespace Fitch\UserBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use AuthorisedClientTrait;

    public function testAccess()
    {
        $users = [
            '' => 302,
            'xuser' => 403,
            'xdisabled' => 403,
            'xeditor' => 403,
            'xadmin' => 403,
            'xsuper' => 200,
        ];

        $this->checkAccess('GET', '/user/', $users);
    }

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xsuper');

        // Create a new entry in the database
        $crawler = $client->request('GET', '/user/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /user/");
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'fitch_userbundle_user_new[userName]'  => 'xtest',
            'fitch_userbundle_user_new[fullName]'  => 'Test User Created',
            'fitch_userbundle_user_new[email]'  => 'test@example.com',
            'fitch_userbundle_user_new[plainPassword]'  => 'test',
            'fitch_userbundle_user_new[roles]'  => ['ROLE_SENSITIVE_DATA'],
            'fitch_userbundle_user_new[enabled]'  => true,
        ));

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

        $form = $crawler->selectButton('Update')->form(array(
            'fitch_userbundle_user[userName]'  => 'xtest-edit',
            'fitch_userbundle_user[fullName]'  => 'Test User Edited',
            'fitch_userbundle_user[email]'  => 'test-edit@example.com',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('[value="xtest-edit"]')->count(),
            'Missing element [value="xtest-edit"]'
        );

        $this->assertGreaterThan(
            0,
            $crawler->filter('[value="Test User Edited"]')->count(),
            'Missing element [value="Test User Edited"]'
        );

        $this->assertGreaterThan(
            0,
            $crawler->filter('[value="test-edit@example.com"]')->count(),
            'Missing element [value="test-edit@example.com"]'
        );

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/xtest-edit/', $client->getResponse()->getContent());
    }
}
