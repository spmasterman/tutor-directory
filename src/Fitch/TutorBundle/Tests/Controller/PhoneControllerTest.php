<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Controller\PhoneController;
use Fitch\TutorBundle\Entity\Phone;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PhoneControllerTest.
 */
class PhoneControllerTest extends FixturesWebTestCase
{
    use AssertBadRequestJsonResponseTrait;

    /**
     * Removed the Phone.
     *
     * @param Phone $phone
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function performMockedRemove(Phone $phone)
    {
        $request = $this->getMockedRequest([
            'pk' => $phone->getId(),
        ]);

        // Call the Controller Update
        $controller = new PhoneController();
        $controller->setContainer($this->container);

        return $controller->removeAction($request);
    }

    /**
     * Test that we can remove a Phone, and that we cant remove a non existent one.
     */
    public function testRemove()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        /** @var Phone $phone */
        $phone = $tutor->getPhoneNumbers()->first();

        $this->performMockedRemove($phone);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->assertNotContains(
            $phone,
            $tutor->getPhoneNumbers()
        );

        // Now try removing it again - should throw an error
        $response = $this->performMockedRemove($phone);

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
