<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Controller\EmailController;
use Fitch\TutorBundle\Entity\Email;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EmailControllerTest.
 */
class EmailControllerTest extends FixturesWebTestCase
{
    use AssertBadRequestJsonResponseTrait;

    /**
     * Removed the Email.
     *
     * @param Email $email
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function performMockedRemove(Email $email)
    {
        $request = $this->getMockedRequest([
            'pk' => $email->getId(),
        ]);

        // Call the Controller Update
        $controller = new EmailController();
        $controller->setContainer($this->container);

        return $controller->removeAction($request);
    }

    /**
     * Test that we can remove a Email, and that we cant remove a non existent one.
     */
    public function testRemove()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        /** @var Email $email */
        $email = $tutor->getEmailAddresses()->first();

        $this->performMockedRemove($email);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->assertNotContains(
            $email,
            $tutor->getEmailAddresses()
        );

        // Now try removing it again - should throw an error
        $response = $this->performMockedRemove($email);

        $this->assertBadRequestJsonResponse($response);
    }

    /**
     * @param array $parameters
     *
     * @return Request
     */
    private function getMockedRequest($parameters)
    {
        // Create a request payload that should update the competency
        $requestBag = new ParameterBag($parameters);

        // Set that up in a Stub/mock Request
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        /* @var Request $request */
        $request->request = $requestBag;

        return $request;
    }

    /**
     * @return TutorManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }
}
