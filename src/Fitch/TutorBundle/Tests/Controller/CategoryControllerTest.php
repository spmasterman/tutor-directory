<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryControllerTest
 */
class CategoryControllerTest extends WebTestCase
{
    use AuthorisedClientTrait;

    /**
     *
     */
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

        $this->checkAccess('GET', '/admin/category/', $users);
    }

    /**
     *
     */
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xadmin');

        /*
         * Test "Get List"
         */

        $crawler = $client->request('GET', '/admin/category/');
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode(),
            "Unexpected HTTP status code for GET /admin/category/"
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
            'fitch_tutorbundle_category[name]'  => 'Test Category One',
            'fitch_tutorbundle_category[businessArea]' => 1,
        ];

        // Form wont have any elements for checkboxes that aren't ticked (by default) so we cant check for them...
        $formCheckBoxes = [
            'fitch_tutorbundle_category[default]'  => true,
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

        // We've created a duplicate Category name - this should fail. We do it here, because its easy, to submit
        // corrected data - just check its not been saved.

        // Validators won't have run (so we wont get .has-error classes in the DOM or anything, but DB
        // should have thrown up (SQLite will handle the "unique" hint by creating an index, which will choke.)
        // The form wont redirect - so just assert that we haven't been redirected. This is problematic because we
        // don't know the id of the new entity
        //        $this->assertFalse(
        //            $client->getResponse()->isRedirect('/category/show/4')
        //        );
        // it makes the test very brittle to hard code it - so just test we didn't get a 200 OK response
        $this->assertEquals(
            Response::HTTP_OK, //not redirect
            $client->getResponse()->getStatusCode(),
            'Form appears to have allowed us created a Duplicate category name - please check the validators'
        );

        // Correct the mistake, resubmit the form
        $formDataToSubmit['fitch_tutorbundle_category[name]'] = 'xtest';
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
            'fitch_tutorbundle_category[name]'  => 'xtest-edit',
            'fitch_tutorbundle_category[businessArea]' => 2,
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
            'fitch_tutorbundle_category[name]'  => 'Test Category One', //dupe
            'fitch_tutorbundle_category[businessArea]'  => 1,
        ));

        // Submit the form
        $client->submit($form);

        // We're not following a redirect - as the form should have choked and we should be back on the
        // same page - the form field "name" should contain its original value - we'll just check it doesn't
        // contain the incorrect one
        $formValues = $crawler->selectButton('Update')->form()->getValues();
        $this->assertNotEquals(
            'Test Category One',
            $formValues['fitch_tutorbundle_category[name]'],
            'Form appears to have allowed us updated to a Duplicate category name - please check the validators'
        );

        /*
         * Test Delete the entity
         */

        $client->submit($crawler->selectButton('Delete')->form());
        $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/xtest-edit/', $client->getResponse()->getContent());

        /*
         * Test updating a non existent Category
         */

        // Try spoofing the form with an unknown ID
        $crawler = $client->request('PUT', '/admin/category/999');
        $exceptionThrown = ($crawler->filter('html:contains("NotFoundHttpException")')->count() > 0)
            && ($crawler->filter('html:contains("Fitch\TutorBundle\Entity\Category object not found.")')->count() > 0);
        $this->assertTrue($exceptionThrown, "Exception thrown 'Unable to find Category entity'");
    }
}
