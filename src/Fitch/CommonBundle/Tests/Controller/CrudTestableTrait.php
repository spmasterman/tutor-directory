<?php

namespace Fitch\CommonBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Field\ChoiceFormField;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait CrudTestableTrait.
 *
 * Think I might be abusing Traits a bit here - or perhaps they are just a bit dangerous.
 *
 * This trait will only work if its included something that also uses
 * AuthorisedClientTrait, and extends KernelTestCase. There's no way in code for me to express this.
 * So I'm re-hinting with the at-var annotation to make it explicit. But its not pretty.
 */
trait CrudTestableTrait
{
    /**
     * @param CrudTestConfig $crudTestConfig
     */
    public function performCrudTest(CrudTestConfig $crudTestConfig)
    {
        /** @var Client $client */
        /** @var AuthorisedClientTrait $this */
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient($crudTestConfig->getUser());

        /** @var KernelTestCase $this */

        // Test "Get List"
        $crawler = $client->request('GET', $crudTestConfig->getUrl());
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode(),
            "Unexpected HTTP status code for GET {$crudTestConfig->getUrl()}"
        );

        // Test "Create New"
        // navigate to the page
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // get array of the current form values
        $formValues = $crawler->selectButton('Create')->form()->getValues();

        // Form wont have any elements for checkboxes that aren't ticked (by default) so we cant check for them...
        $formCheckBoxes = $crudTestConfig->getCheckBoxes();

        if ($crudTestConfig->areUniqueChecksEnabled()) {
            /** @var CrudTestableTrait $this */
            $crawler = $this->performUniqueTests($crudTestConfig, $formValues, $crawler, $formCheckBoxes, $client);
        }

        // Correct the mistake, resubmit the form
        $formDataToSubmit = $crudTestConfig->getFixedCreateFormData();
        $form = $crawler->selectButton('Create')->form($formDataToSubmit);
        $client->submit($form);
        $crawler = $client->followRedirect();

        $check = $crudTestConfig->getCheckCreateFunction();
        $check($crawler);

        // Test "Edit" the entity
        // navigate to the edit page
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        // grab the form, will it with edited data
        $form = $crawler->selectButton('Update')->form($crudTestConfig->getFixedEditFormData());

        // ...and manually tick() the check boxes
        foreach (array_keys($formCheckBoxes) as $key) {
            /** @var ChoiceFormField $choiceField */
            $choiceField = $form[$key];
            $choiceField->untick();
        }

        // press submit
        $client->submit($form);
        $crawler = $client->followRedirect();

        // and check that the new value is on the page
        $check = $crudTestConfig->getCheckEditFunction();
        $check($crawler);

        // Try an invalid form
        $form = $crawler->selectButton('Update')->form($crudTestConfig->getBadEditFormData());
        $client->submit($form);

        // We're not following a redirect - as the form should have choked and we should be back on the
        // same page - the CheckBadEdit function should pass
        $formValues = $crawler->selectButton('Update')->form()->getValues();

        $check = $crudTestConfig->getCheckBadEditFunction();
        $check($formValues);

        // Test "Delete"
        $client->submit($crawler->selectButton('Delete')->form());
        $client->followRedirect();

        // Check the entity has been delete on the list
        $check = $crudTestConfig->getCheckDeletedFunction();
        $check($client->getResponse()->getContent());

        // Test "Updating" a non existent Entity
        // Try spoofing the form with an unknown ID
        $crawler = $client->request('PUT', $crudTestConfig->getUrl().'999');

        $check = $crudTestConfig->getCheckBadUpdateFunction();
        $check($crawler);
    }

    /**
     * @param CrudTestConfig $crudTestConfig
     * @param array          $formValues
     * @param Crawler        $crawler
     * @param array          $formCheckBoxes
     * @param Client         $client
     *
     * @return Crawler
     */
    public function performUniqueTests(CrudTestConfig $crudTestConfig, $formValues, $crawler, $formCheckBoxes, $client)
    {
        // here's our test data - we have a duplicate "name" - this should choke
        $formDataToSubmit = $crudTestConfig->getBadCreateFormData();

        // but we can check for everything else
        foreach (array_keys($formDataToSubmit) as $key) {
            /** @var KernelTestCase $this */
            $this->assertArrayHasKey($key, $formValues, $key.' not in ['.implode(', ', array_keys($formValues)));
        }

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form($formDataToSubmit);

        // ...and manually tick() the check boxes
        foreach (array_keys($formCheckBoxes) as $key) {
            /** @var ChoiceFormField $choiceField */
            $choiceField = $form[$key];
            $choiceField->tick();
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

        return $crawler;
    }
}
