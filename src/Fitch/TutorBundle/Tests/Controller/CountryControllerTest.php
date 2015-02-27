<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Field\ChoiceFormField;

class CountryControllerTest extends WebTestCase
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

        $this->checkAccess('GET', '/admin/country/', $users);
    }

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xadmin');

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/country/');
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode(),
            "Unexpected HTTP status code for GET /user/"
        );

        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        $formValues = $crawler->selectButton('Create')->form()->getValues();

        $formDataToSubmit = [
            'fitch_tutorbundle_country[name]'  => 'xtest',
            'fitch_tutorbundle_country[twoDigitCode]'  => 'xt',
            'fitch_tutorbundle_country[threeDigitCode]'  => 'xtt',
            'fitch_tutorbundle_country[dialingCode]'  => '+1',
            'fitch_tutorbundle_country[defaultRegion]' => 1,
        ];

        $formCheckBoxes = [
            'fitch_tutorbundle_country[preferred]'  => true,
            'fitch_tutorbundle_country[active]'  => true,
        ];

        // Form wont have any elements for checkboxes that aren't ticked (by default) so we cant check for them...
        foreach (array_keys($formDataToSubmit) as $key) {
            $this->assertArrayHasKey($key, $formValues, $key.' not in ['.implode(', ', array_keys($formValues)));
        }

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form($formDataToSubmit);

        // ...and manually tick() the check boxes
        foreach (array_keys($formCheckBoxes) as $key) {
            $form[$key]->tick();
        }

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
            'fitch_tutorbundle_country[name]'  => 'xtest-edit',
            'fitch_tutorbundle_country[twoDigitCode]'  => 'xe',
            'fitch_tutorbundle_country[threeDigitCode]'  => 'xte',
            'fitch_tutorbundle_country[dialingCode]'  => '+44',
            'fitch_tutorbundle_country[defaultRegion]' => 2,
        ));

        // ...and manually tick() the check boxes
        foreach (array_keys($formCheckBoxes) as $key) {
            $form[$key]->untick();
        }

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
