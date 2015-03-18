<?php

namespace Fitch\CommonBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

trait CrudTestableTrait
{
    /**
     * @param CrudTestConfig $crudTestConfig
     */
    public function performCrudTest(CrudTestConfig $crudTestConfig)
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient($crudTestConfig->getUser());

        /*
         * Test "Get List"
         */

        $crawler = $client->request('GET', $crudTestConfig->getUrl());
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode(),
            "Unexpected HTTP status code for GET {$crudTestConfig->getUrl()}"
        );

        /*
         * Test Create New
         */

        // navigate to the page
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // get array of the current form values
        $formValues = $crawler->selectButton('Create')->form()->getValues();

        // Form wont have any elements for checkboxes that aren't ticked (by default) so we cant check for them...
        $formCheckBoxes = $crudTestConfig->getCheckBoxes();

        if ($crudTestConfig->areUniqueChecksEnabled()) {
            // here's our test data - we have a duplicate "name" - this should choke
            $formDataToSubmit = $crudTestConfig->getFormData();

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

            // We've created a duplicate Entity name - this should fail. We do it here, because its easy, to submit
            // corrected data - just check its not been saved.

            // Validators won't have run (so we wont get .has-error classes in the DOM or anything, but DB
            // should have thrown up (SQLite will handle the "unique" hint by creating an index, which will choke.)
            // The form wont redirect - so just assert that we haven't been redirected. This is problematic because we
            // don't know the id of the new entity
            //        $this->assertFalse(
            //            $client->getResponse()->isRedirect('/country/show/4')
            //        );
            // it makes the test very brittle to hard code it - so just test we got a 200 OK response
            $this->assertEquals(
                Response::HTTP_OK, //not redirect
                $client->getResponse()->getStatusCode(),
                'Form appears to have allowed us created a Duplicate Entity - please check the validators'
            );
        }

        // Correct the mistake, resubmit the form
        $formDataToSubmit = $crudTestConfig->getFixedFormData();
        $form = $crawler->selectButton('Create')->form($formDataToSubmit);
        $client->submit($form);
        $crawler = $client->followRedirect();

        $check = $crudTestConfig->getCheckAdditionFunction();
        $check($crawler);

        /*
         * Test Edit the entity
         */

        // navigate to the edit page
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        // grab the form, will it with edited data
        $form = $crawler->selectButton('Update')->form($crudTestConfig->getEditFormData());

        // ...and manually tick() the check boxes
        foreach (array_keys($formCheckBoxes) as $key) {
            $form[$key]->untick();
        }

        // press submit
        $client->submit($form);
        $crawler = $client->followRedirect();

        // and check that the new value is on the page

        $check = $crudTestConfig->getCheckEditFunction();
        $check($crawler);

        // Try an invalid form
        $form = $crawler->selectButton('Update')->form($crudTestConfig->getBadEditFormData());

        // Submit the form
        $client->submit($form);

        // We're not following a redirect - as the form should have choked and we should be back on the
        // same page - the CheckBadEdit function should pass
        $formValues = $crawler->selectButton('Update')->form()->getValues();

        $check = $crudTestConfig->getCheckBadEditFunction();
        $check($formValues);

        /*
         * Test Delete the entity
         */

        $client->submit($crawler->selectButton('Delete')->form());
        $client->followRedirect();

        // Check the entity has been delete on the list
        $check = $crudTestConfig->getCheckDeletedFunction();
        $check($client->getResponse()->getContent());

        /*
         * Test updating a non existent Country
         */

        // Try spoofing the form with an unknown ID
        $crawler = $client->request('PUT', $crudTestConfig->getUrl().'999');

        $check = $crudTestConfig->getCheckBadUpdateFunction();
        $check($crawler);
    }
}
