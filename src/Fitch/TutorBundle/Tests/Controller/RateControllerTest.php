<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Controller\RateController;
use Fitch\TutorBundle\Entity\Rate;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RateControllerTest.
 */
class RateControllerTest extends FixturesWebTestCase
{
    use AssertBadRequestJsonResponseTrait;

    /**
     * Removed the Rate.
     *
     * @param Rate $rate
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function performMockedRemove(Rate $rate)
    {
        $request = $this->getMockedRequest([
            'pk' => $rate->getId(),
        ]);

        // Call the Controller Update
        $controller = new RateController();
        $controller->setContainer($this->container);

        return $controller->removeAction($request);
    }

    /**
     * Test that we can remove a Rate, and that we cant remove a non existent one.
     */
    public function testRemove()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        /** @var Rate $rate */
        $rate = $tutor->getRates()->first();

        $this->performMockedRemove($rate);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->assertNotContains(
            $rate,
            $tutor->getRates()
        );

        // Now try removing it again - should throw an error
        $response = $this->performMockedRemove($rate);

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
