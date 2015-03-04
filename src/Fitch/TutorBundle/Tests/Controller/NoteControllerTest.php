<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Controller\NoteController;
use Fitch\TutorBundle\Entity\Note;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NoteControllerTest.
 */
class NoteControllerTest extends FixturesWebTestCase
{
    use AssertBadRequestJsonResponseTrait;

    /**
     * Removed the Note.
     *
     * @param Note $note
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function performMockedRemove(Note $note)
    {
        $request = $this->getMockedRequest([
            'pk' => $note->getId(),
        ]);

        // Call the Controller Update
        $controller = new NoteController();
        $controller->setContainer($this->container);

        return $controller->removeAction($request);
    }

    /**
     * Test that we can remove a Note, and that we cant remove a non existent one.
     */
    public function testRemove()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        /** @var Note $note */
        $note = $tutor->getNotes()->first();

        $this->performMockedRemove($note);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->assertNotContains(
            $note,
            $tutor->getNotes()
        );

        // Now try removing it again - should throw an error
        $response = $this->performMockedRemove($note);

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
