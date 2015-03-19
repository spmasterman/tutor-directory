<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Fitch\TutorBundle\Model\CurrencyManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;

/**
 * Class ProfileControllerTest.
 */
class ProfileControllerTest extends FixturesWebTestCase
{
    use AuthorisedClientTrait, ProfileMockedUpdateTrait, AssertBadRequestJsonResponseTrait;

    /**
     * Test deleting a tutor from the profile page
     */
    public function testDeleteTutor()
    {
        $deleteButtonLabel = "Delete Trainer";

        // check that unauthorised users cant delete
        $client = $this->createAuthorizedClient('xuser');
        $crawler = $client->request('GET', '/profile/1');

        // should be a delete button on the page
        $this->assertCount(
            0,
            $crawler->filter("button:contains('$deleteButtonLabel')")
        );

        // try spoofing the Delete action, anyhow
        $client->request('DELETE', '/profile/1');

        // check we're forbidden from being naughty
        $this->assertEquals(
            403,
            $client->getResponse()->getStatusCode(),
            "Did not get 403 as unauthorised user"
        );

        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xsuper');

        $crawler = $client->request('GET', '/profile/1');

        // Check we can see the page
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode(),
            "Unexpected HTTP status code for GET '/profile/1'"
        );

        // Check super admin can see delete button
        $this->assertCount(
            1,
            $crawler->filter("button:contains('$deleteButtonLabel')")
        );

        // Press it
        $form = $crawler->selectButton($deleteButtonLabel)->form();
        $client->submit($form);

        // Check we redirect, no need to follow it, just that we get a "temporary redirect"
        $this->assertEquals(
            302,
            $client->getResponse()->getStatusCode(),
            "Did not get 302 after deleting"
        );

        // Now check that we get a 404 when we try view the same entity
        $client->request('GET', '/profile/1');
        $this->assertEquals(
            404,
            $client->getResponse()->getStatusCode(),
            "Unexpected HTTP status code for GET '/profile/1' after deletion"
        );
    }

    /**
     * Test editing a real member (f.ex. company)
     */
    public function testUpdateRealMember()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        $tutor->setCompany(TestSlug::START_1);
        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->assertEquals(TestSlug::START_1, $tutor->getCompany());

        $this->performMockedUpdate($tutor, 'company', [
            'value' => TestSlug::START_2,
        ]);

        $manager->reloadEntity($tutor);

        $this->assertEquals(TestSlug::START_2, $tutor->getCompany());
    }


    /**
     * Test editing a fake member (f.ex. banana)
     */
    public function testUpdateFakeMember()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        $response = $this->performMockedUpdate($tutor, 'banana', [
            'value' => TestSlug::START_1,
        ]);

        $this->assertBadRequestJsonResponse($response);
    }

    /**
     * @return TutorManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }

    /**
     * @return CurrencyManagerInterface
     */
    public function getCurrencyManager()
    {
        return $this->container->get('fitch.manager.currency');
    }
}
