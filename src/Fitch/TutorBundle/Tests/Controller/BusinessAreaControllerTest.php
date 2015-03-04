<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BusinessAreaControllerTest extends WebTestCase
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

        $this->checkAccess('GET', '/admin/business_area/', $users);
    }

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xadmin');

        /*
         * Test "Get List"
         */

        $crawler = $client->request('GET', '/admin/business_area/');
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode(),
            "Unexpected HTTP status code for GET /admin/business_area/"
        );

        /*
         * Test Create New
         */

        // navigate to the page
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // get array of the current form values
        $formValues = $crawler->selectButton('Create')->form()->getValues();

        // here's our test data - we have a duplicate "name" - this should choke
        $formDataToSubmit = [
            'fitch_tutorbundle_business_area[name]'  => 'Test Business Area 1',
            'fitch_tutorbundle_business_area[code]'  => 'XX',
        ];

        // Form wont have any elements for checkboxes that aren't ticked (by default) so we cant check for them...
        $formCheckBoxes = [
            'fitch_tutorbundle_business_area[prependToCategoryName]' => true,
            'fitch_tutorbundle_business_area[displayAsCode]'  => true,
            'fitch_tutorbundle_business_area[default]'  => true,
        ];

        // but we can check for everything else
        foreach (array_keys($formDataToSubmit) as $key) {
            $this->assertArrayHasKey($key, $formValues, $key.' not in ['.implode(', ', array_keys($formValues)));
        }

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form($formDataToSubmit);

        // ...and manually tick() the check boxes
        foreach (array_keys($formCheckBoxes) as $key) {
            $form[$key]->tick();
        }

        // submit the form
        $crawler = $client->submit($form);

        // We've created a duplicate Business Area name - this should fail. We do it here, because its easy, to submit
        // corrected data - just check its not been saved.

        // Validators won't have run (so we wont get .has-error classes in the DOM or anything, but DB
        // should have thrown up (SQLite will handle the "unique" hint by creating an index, which will choke.)
        // The form wont redirect - so just assert that we haven't been redirected. This is problematic because we
        // don't know the id of the new entity
        //        $this->assertFalse(
        //            $client->getResponse()->isRedirect('/entity/show/4')
        //        );
        // it makes the test very brittle to hard code it - so just test we didn't get a 200 OK response
        $this->assertEquals(
            Response::HTTP_OK, //not redirect
            $client->getResponse()->getStatusCode(),
            'Form appears to have allowed us created a Duplicate Business Area name - please check the validators'
        );

        // Correct the mistake, resubmit the form
        $formDataToSubmit['fitch_tutorbundle_business_area[name]'] = 'xtest';
        $form = $crawler->selectButton('Create')->form($formDataToSubmit);
        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the "Show" view
        $this->assertGreaterThan(
            0,
            $crawler->filter('td:contains("xtest")')->count(),
            'Missing element td:contains("Test")'
        );

        /*
         * Test Edit the entity
         */

        // navigate to the edit page
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        // grab the form, will it with edited data
        $form = $crawler->selectButton('Update')->form(array(
            'fitch_tutorbundle_business_area[name]'  => 'xtest-edit',
            'fitch_tutorbundle_business_area[code]'  => 'XX',
        ));

        // ...and manually tick() the check boxes
        foreach (array_keys($formCheckBoxes) as $key) {
            $form[$key]->untick();
        }

        // press submit
        $client->submit($form);
        $crawler = $client->followRedirect();

        // and check that the new value is on the page
        $this->assertGreaterThan(
            0,
            $crawler->filter('[value="xtest-edit"]')->count(),
            'Missing element [value="xtest-edit"]'
        );

        // Try an invalid form - we'll use the same duplicate value we used earlier
        $form = $crawler->selectButton('Update')->form(array(
            'fitch_tutorbundle_business_area[name]'  => 'Test Business Area 1',
            'fitch_tutorbundle_business_area[code]'  => 'XX',
        ));

        // Submit the form
        $client->submit($form);

        // We're not following a redirect - as the form should have choked and we should be back on the
        // same page - the form field "name" should contain its original value - we'll just check it doesn't
        // contain the incorrect one
        $formValues = $crawler->selectButton('Update')->form()->getValues();
        $this->assertNotEquals(
            'Test Business Area 1',
            $formValues['fitch_tutorbundle_business_area[name]'],
            'Form appears to have allowed us updated to a Duplicate Business Area name - please check the validators'
        );

        /*
         * Test Delete the entity
         */

        $client->submit($crawler->selectButton('Delete')->form());
        $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/xtest-edit/', $client->getResponse()->getContent());

        /*
         * Test updating a non existent BusinessArea
         */

        // Try spoofing the form with an unknown ID
        $crawler = $client->request('PUT', '/admin/business_area/999');
        $exceptionThrown = ($crawler->filter('html:contains("NotFoundHttpException")')->count() > 0)
            && ($crawler->filter('html:contains("Fitch\TutorBundle\Entity\BusinessArea object not found.")')->count() > 0);
        $this->assertTrue($exceptionThrown, "Exception thrown 'Unable to find BusinessArea entity'");
    }
}
