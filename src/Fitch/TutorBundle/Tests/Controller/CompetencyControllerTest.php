<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Controller\CompetencyController;
use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

/**
 * Class CompetencyControllerTest.
 */
class CompetencyControllerTest extends FixturesWebTestCase
{
    const START = 'START';
    const END = 'END';

    /**
     * @param Tutor $tutor
     * @param Competency $competency
     * @param string $name
     * @param string $value
     *
     * This should throw an Authentication type error - that's OK its because its trying to render some
     * template that has content that is dependant on the current user. We don't care about this bit - were
     * trying to test the body of the controller. We could (possibly) mock out the security system, but there's
     * no value doing that.
     */
    private function performMockedUpdate(Tutor $tutor, Competency $competency, $name, $value = self::END)
    {
        // Create a request payload that should update the competency
        $requestBag = new ParameterBag([
            'pk' => $tutor->getId(),
            'competencyPk' => $competency->getId(),
            'name' => $name,
            'value' => $value,
        ]);

        // Set that up in a Stub/mock Request
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $request->request = $requestBag;

        // Call the Controller Update
        $controller = new CompetencyController();
        $controller->setContainer($this->container);

        try {
            $response = $controller->updateAction($request);
        } catch (AuthenticationCredentialsNotFoundException $e) {
            $response = null;
        }

        return $response;
    }

    /**
     * Test editing a Note via a (mock)request object being passed to update controller method.
     */
    public function testUpdatingNote()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // Set the Note on his first competency, to the START tag
        /** @var Competency $competency */
        $competency = $tutor->getCompetencies()->first();
        $competency->setNote(self::START);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(self::START, $tutor->getCompetencies()->first()->getNote());

        $this->performMockedUpdate($tutor, $competency, 'competency-note');

        $manager->reloadEntity($tutor);
        $this->assertEquals(self::END, $tutor->getCompetencies()->first()->getNote());
    }

    /**
     * Test Setting a New CompetencyType via a (mock)request object being passed to update controller method.
     */
    public function testUpdatingType()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // Set the Note on his first competency, to the START tag
        /** @var Competency $competency */
        $competency = $tutor->getCompetencies()->first();
        $competency->getCompetencyType()->setName(self::START);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(self::START, $tutor->getCompetencies()->first()->getCompetencyType()->getName());

        $originalId = $tutor->getCompetencies()->first()->getCompetencyType()->getId();

        $this->performMockedUpdate($tutor, $competency, 'competency-type');

        $manager->reloadEntity($tutor);
        $this->assertEquals(self::END, $tutor->getCompetencies()->first()->getCompetencyType()->getName());

        // now change it back, but by ID
        $this->performMockedUpdate($tutor, $competency, 'competency-type', $originalId);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(self::START, $tutor->getCompetencies()->first()->getCompetencyType()->getName());
    }

    /**
     * Test Setting a New CompetencyLevel via a (mock)request object being passed to update controller method.
     */
    public function testUpdatingLevel()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // Set the Note on his first competency, to the START tag
        /** @var Competency $competency */
        $competency = $tutor->getCompetencies()->first();
        $competency->getCompetencyLevel()->setName(self::START);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(self::START, $tutor->getCompetencies()->first()->getCompetencyLevel()->getName());

        $originalId = $tutor->getCompetencies()->first()->getCompetencyLevel()->getId();

        $this->performMockedUpdate($tutor, $competency, 'competency-level');

        $manager->reloadEntity($tutor);
        $this->assertEquals(self::END, $tutor->getCompetencies()->first()->getCompetencyLevel()->getName());

        // now change it back, but by ID
        $this->performMockedUpdate($tutor, $competency, 'competency-level', $originalId);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(self::START, $tutor->getCompetencies()->first()->getCompetencyLevel()->getName());
    }

    /**
     * Test Setting a New CompetencyLevel via a (mock)request object being passed to update controller method.
     */
    public function testInvalidUpdate()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // Set the Note on his first competency, to the START tag
        /** @var Competency $competency */
        $competency = $tutor->getCompetencies()->first();

        $response = $this->performMockedUpdate($tutor, $competency, 'competency-banana');
        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $this->assertEquals(
            Response::HTTP_BAD_REQUEST,
            $response->getStatusCode()
        );
    }


    /**
     * Removed the competency. Doesn't have the same issue as updating it, as we dont need to render an updated row
     * to send to the view
     *
     * @param Competency $competency
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function performMockedRemove(Competency $competency)
    {
        // Create a request payload that should remove the competency
        $requestBag = new ParameterBag([
            'pk' => $competency->getId(),
        ]);

        // Set that up in a Stub/mock Request
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $request->request = $requestBag;

        // Call the Controller Update
        $controller = new CompetencyController();
        $controller->setContainer($this->container);

        return $controller->removeAction($request);
    }

    /**
     * Test that we can remove a competency, and that we cant remove a non existent one
     */
    public function testRemove()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // Set the Note on his first competency, to the START tag
        /** @var Competency $competency */
        $competency = $tutor->getCompetencies()->first();

        $this->performMockedRemove($competency);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->assertNotContains(
            $competency,
            $tutor->getCompetencies()
        );

        // Now try removing it again - should throw an error
        $response = $this->performMockedRemove($competency);
        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $this->assertEquals(
            Response::HTTP_BAD_REQUEST,
            $response->getStatusCode()
        );
    }

    /**
     * @return TutorManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }
}
