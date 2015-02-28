<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Controller\CompetencyController;
use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class CompetencyControllerTest extends FixturesWebTestCase
{
    const START = 'START';
    const END = 'END';

    /**
     * @param Tutor      $tutor
     * @param Competency $competency
     * @param string     $name
     *
     * This should throw an Authentication type error - that's OK its because its trying to render some
     * template that has content that is dependant on the current user. We don't care about this bit - were
     * trying to test the body of the controller. We could (possibly) mock out the security system, but there's
     * no value doing that.
     */
    private function performMockedUpdate(Tutor $tutor, Competency $competency, $name)
    {
        // Create a response payload that should change the Note
        $requestBag = new ParameterBag([
            'pk' => $tutor->getId(),
            'competencyPk' => $competency->getId(),
            'name' => $name,
            'value' => self::END,
        ]);

        // Set that up in a Stub/mock Request
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $request->request = $requestBag;

        // Call the Controller Update
        $controller = new CompetencyController();
        $controller->setContainer($this->container);

        try {
            $controller->updateAction($request);
        } catch (AuthenticationCredentialsNotFoundException $e) {
            // Do nothing
        }
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
        $manager->refreshTutor($tutor);
        $this->assertEquals(self::START, $tutor->getCompetencies()->first()->getNote());

        $this->performMockedUpdate($tutor, $competency, 'competency-note');

        $manager->refreshTutor($tutor);
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
        $manager->refreshTutor($tutor);
        $this->assertEquals(self::START, $tutor->getCompetencies()->first()->getCompetencyType()->getName());

        $this->performMockedUpdate($tutor, $competency, 'competency-type');

        $manager->refreshTutor($tutor);
        $this->assertEquals(self::END, $tutor->getCompetencies()->first()->getCompetencyType()->getName());
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
        $manager->refreshTutor($tutor);
        $this->assertEquals(self::START, $tutor->getCompetencies()->first()->getCompetencyLevel()->getName());

        $this->performMockedUpdate($tutor, $competency, 'competency-level');

        $manager->refreshTutor($tutor);
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
