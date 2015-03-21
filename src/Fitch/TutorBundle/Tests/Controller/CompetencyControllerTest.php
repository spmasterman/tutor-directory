<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Controller\CompetencyController;
use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

/**
 * Class CompetencyControllerTest.
 */
class CompetencyControllerTest extends FixturesWebTestCase
{
    use AssertBadRequestJsonResponseTrait;

    private $savedService;

    /**
     * @param Tutor      $tutor
     * @param Competency $competency
     * @param string     $name
     * @param string     $value
     *
     * @return null|\Symfony\Component\HttpFoundation\JsonResponse
     */
    private function performMockedUpdate(Tutor $tutor, Competency $competency, $name, $value = TestSlug::END_1)
    {
        $request = $this->getMockedRequest([
            'pk' => $tutor->getId(),
            'competencyPk' => $competency->getId(),
            'name' => $name,
            'value' => $value,
        ]);

        // Call the Controller Update
        $controller = new CompetencyController();
        $this->injectAuthService(true);
        $controller->setContainer($this->container);

        try {
            $response = $controller->updateAction($request);
        } catch (AuthenticationCredentialsNotFoundException $e) {
            $response = null;
        }

        $this->restoreContainer();

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
        $competency->setNote(TestSlug::START_1);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::START_1, $tutor->getCompetencies()->first()->getNote());

        $this->performMockedUpdate($tutor, $competency, 'competency-note');

        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::END_1, $tutor->getCompetencies()->first()->getNote());
    }

    /**
     * Test Setting a New CompetencyType via a (mock)request object being passed to update controller method.
     */
    public function testUpdatingType()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // Set the Type on his first competency, to the START tag
        /** @var Competency $competency */
        $competency = $tutor->getCompetencies()->first();
        $competency->getCompetencyType()->setName(TestSlug::START_1);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::START_1, $tutor->getCompetencies()->first()->getCompetencyType()->getName());

        $originalId = $tutor->getCompetencies()->first()->getCompetencyType()->getId();

        $this->performMockedUpdate($tutor, $competency, 'competency-type');

        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::END_1, $tutor->getCompetencies()->first()->getCompetencyType()->getName());

        // now change it back, but by ID
        $this->performMockedUpdate($tutor, $competency, 'competency-type', $originalId);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::START_1, $tutor->getCompetencies()->first()->getCompetencyType()->getName());
    }

    /**
     * Test Setting a New CompetencyLevel via a (mock)request object being passed to update controller method.
     */
    public function testUpdatingLevel()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // Set the Level on his first competency, to the START tag
        /** @var Competency $competency */
        $competency = $tutor->getCompetencies()->first();
        $competency->getCompetencyLevel()->setName(TestSlug::START_1);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::START_1, $tutor->getCompetencies()->first()->getCompetencyLevel()->getName());

        $originalId = $tutor->getCompetencies()->first()->getCompetencyLevel()->getId();

        $this->performMockedUpdate($tutor, $competency, 'competency-level');

        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::END_1, $tutor->getCompetencies()->first()->getCompetencyLevel()->getName());

        // now change it back, but by ID
        $this->performMockedUpdate($tutor, $competency, 'competency-level', $originalId);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::START_1, $tutor->getCompetencies()->first()->getCompetencyLevel()->getName());
    }

    /**
     * Test Setting an invalid field via a (mock)request object being passed to update controller method.
     */
    public function testInvalidUpdate()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        /** @var Competency $competency */
        $competency = $tutor->getCompetencies()->first();

        $response = $this->performMockedUpdate($tutor, $competency, 'competency-banana');

        $this->assertBadRequestJsonResponse($response);
    }

    /**
     * Removed the competency. Doesn't have the same issue as updating it, as we dont need to render an updated row
     * to send to the view.
     *
     * @param Competency $competency
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function performMockedRemove(Competency $competency)
    {
        $request = $this->getMockedRequest([
            'pk' => $competency->getId(),
        ]);

        // Call the Controller Update
        $controller = new CompetencyController();
        $controller->setContainer($this->container);

        return $controller->removeAction($request);
    }

    /**
     * Test that we can remove a competency, and that we cant remove a non existent one.
     */
    public function testRemove()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

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
     * This injects a mock into the container in place of the security.authorization_checker service.
     *
     * @param bool $isGranted
     */
    private function injectAuthService($isGranted)
    {
        $mockAuthChecker = $this->getMockBuilder('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mockAuthChecker->expects($this->any())->method('isGranted')->willReturn($isGranted);

        $this->savedService = $this->container->get('security.authorization_checker');
        $this->container->set('security.authorization_checker', $mockAuthChecker);
    }

    private function restoreContainer()
    {
        $this->container->set('security.authorization_checker', $this->savedService);
    }

    /**
     * @return TutorManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }
}
