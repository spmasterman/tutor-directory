<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Controller\CompetencyController;
use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Model\Interfaces\TutorManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class CompetencyControllerTest extends FixturesWebTestCase
{
    const START = 'START';
    const END = 'END';

    /**
     * Test editing a Note via a (mock)request object being passed to update controller method
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

        // Save it and reload it
        $manager->saveTutor($tutor);
        $manager->refreshTutor($tutor);

        // Check that its set
        $this->assertEquals(self::START, $tutor->getCompetencies()->first()->getNote());

        // Create a response payload that should change the Note
        $requestBag = new ParameterBag([
            'pk' => $tutor->getId(),
            'competencyPk' => $competency->getId(),
            'name' => 'competency-note',
            'value' => self::END
        ]);

        // Set that up in a Stub/mock Request
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $request->request = $requestBag;

        // Call the Controller Update
        $controller = new CompetencyController();
        $controller->setContainer($this->container);

        try {
            $controller->updateAction($request);
        } catch(AuthenticationCredentialsNotFoundException $e) {
            // This should throw an Authentication type error - thats OK its because its trying to render some
            // template that has content that is dependant on the current user
            // We dont care about this bit - were trying to test the body of the controller
        }

        // Reload the tutor
        $manager->refreshTutor($tutor);

        // Check that its set
        $this->assertEquals(self::END, $tutor->getCompetencies()->first()->getNote());
    }

    /**
     * Test Setting a New CompetencyType via a (mock)request object being passed to update controller method
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

        // Save it and reload it
        $manager->saveTutor($tutor);
        $manager->refreshTutor($tutor);

        // Check that its set
        $this->assertEquals(self::START, $tutor->getCompetencies()->first()->getCompetencyType()->getName());

        // Create a response payload that should change the Note
        $requestBag = new ParameterBag([
            'pk' => $tutor->getId(),
            'competencyPk' => $competency->getId(),
            'name' => 'competency-type',
            'value' => self::END
        ]);

        // Set that up in a Stub/mock Request
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $request->request = $requestBag;

        // Call the Controller Update
        $controller = new CompetencyController();
        $controller->setContainer($this->container);

        try {
            $controller->updateAction($request);
        } catch(AuthenticationCredentialsNotFoundException $e) {
            // This should throw an Authentication type error - thats OK its because its trying to render some
            // template that has content that is dependant on the current user
            // We dont care about this bit - were trying to test the body of the controller
        }

        // Reload the tutor
        $manager->refreshTutor($tutor);

        // Check that its set
        $this->assertEquals(self::END, $tutor->getCompetencies()->first()->getCompetencyType()->getName());
    }

    /**
     * Test Setting a New CompetencyLevel via a (mock)request object being passed to update controller method
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

        // Save it and reload it
        $manager->saveTutor($tutor);
        $manager->refreshTutor($tutor);

        // Check that its set
        $this->assertEquals(self::START, $tutor->getCompetencies()->first()->getCompetencyLevel()->getName());

        // Create a response payload that should change the Note
        $requestBag = new ParameterBag([
            'pk' => $tutor->getId(),
            'competencyPk' => $competency->getId(),
            'name' => 'competency-level',
            'value' => self::END
        ]);

        // Set that up in a Stub/mock Request
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $request->request = $requestBag;

        // Call the Controller Update
        $controller = new CompetencyController();
        $controller->setContainer($this->container);

        try {
            $controller->updateAction($request);
        } catch(AuthenticationCredentialsNotFoundException $e) {
            // This should throw an Authentication type error - thats OK its because its trying to render some
            // template that has content that is dependant on the current user
            // We dont care about this bit - were trying to test the body of the controller
        }

        // Reload the tutor
        $manager->refreshTutor($tutor);

        // Check that its set
        $this->assertEquals(self::END, $tutor->getCompetencies()->first()->getCompetencyLevel()->getName());
    }

    /**
     * @return TutorManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }
}
