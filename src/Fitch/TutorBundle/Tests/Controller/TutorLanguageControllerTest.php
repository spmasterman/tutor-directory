<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Controller\CompetencyController;
use Fitch\TutorBundle\Controller\TutorLanguageController;
use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Entity\TutorLanguage;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

/**
 * Class TutorLanguageControllerTest.
 */
class TutorLanguageControllerTest extends FixturesWebTestCase
{
    use AssertBadRequestJsonResponseTrait;

    /**
     * @param Tutor      $tutor
     * @param TutorLanguage $tutorLanguage
     * @param string     $name
     * @param string     $value
     *
     * This should throw an Authentication type error - that's OK its because its trying to render some
     * template that has content that is dependant on the current user. We don't care about this bit - were
     * trying to test the body of the controller. We could (possibly) mock out the security system, but there's
     * no value doing that.
     *
     * @return null|\Symfony\Component\HttpFoundation\JsonResponse
     */
    private function performMockedUpdate(Tutor $tutor, TutorLanguage $tutorLanguage, $name, $value = TestSlug::END_1)
    {
        $request = $this->getMockedRequest([
            'pk' => $tutor->getId(),
            'tutorLanguagePk' => $tutorLanguage->getId(),
            'name' => $name,
            'value' => $value,
        ]);

        // Call the Controller Update
        $controller = new TutorLanguageController();
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

        // Set the Note on his first tutorLanguage, to the START tag
        /** @var TutorLanguage $tutorLanguage*/
        $tutorLanguage = $tutor->getTutorLanguages()->first();
        $tutorLanguage->setNote(TestSlug::START_1);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::START_1, $tutor->getTutorLanguages()->first()->getNote());

        $this->performMockedUpdate($tutor, $tutorLanguage, 'tutor-language-note');

        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::END_1, $tutor->getTutorLanguages()->first()->getNote());
    }

    /**
     * Test Setting a New Language via a (mock)request object being passed to update controller method.
     */
    public function testUpdatingLanguage()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // Set the Language on his first competency, to the START tag
        /** @var TutorLanguage $tutorLanguage */
        $tutorLanguage = $tutor->getTutorLanguages()->first();
        $tutorLanguage->getLanguage()->setName(TestSlug::START_1);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::START_1, $tutor->getTutorLanguages()->first()->getLanguage()->getName());

        $originalId = $tutor->getTutorLanguages()->first()->getLanguage()->getId();

        $this->performMockedUpdate($tutor, $tutorLanguage, 'tutor-language');

        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::END_1, $tutor->getTutorLanguages()->first()->getLanguage()->getName());

        // now change it back, but by ID
        $this->performMockedUpdate($tutor, $tutorLanguage, 'tutor-language', $originalId);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::START_1, $tutor->getTutorLanguages()->first()->getLanguage()->getName());
    }

    /**
     * Test Setting a New Proficiency via a (mock)request object being passed to update controller method.
     */
    public function testUpdatingProficiency()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // Set the Proficiency on his first competency, to the START tag
        /** @var TutorLanguage $tutorLanguage */
        $tutorLanguage = $tutor->getTutorLanguages()->first();
        $tutorLanguage->getProficiency()->setName(TestSlug::START_1);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::START_1, $tutor->getTutorLanguages()->first()->getProficiency()->getName());

        $originalId = $tutor->getTutorLanguages()->first()->getProficiency()->getId();

        $this->performMockedUpdate($tutor, $tutorLanguage, 'tutor-language-proficiency');

        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::END_1, $tutor->getTutorLanguages()->first()->getProficiency()->getName());

        // now change it back, but by ID
        $this->performMockedUpdate($tutor, $tutorLanguage, 'tutor-language-proficiency', $originalId);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::START_1, $tutor->getTutorLanguages()->first()->getProficiency()->getName());
    }

    /**
     * Test Setting an invalid field via a (mock)request object being passed to update controller method.
     */
    public function testInvalidUpdate()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        /** @var TutorLanguage $tutorLanguage */
        $tutorLanguage = $tutor->getTutorLanguages()->first();

        $response = $this->performMockedUpdate($tutor, $tutorLanguage, 'tutor-language-banana');

        $this->assertBadRequestJsonResponse($response);
    }

    /**
     * Removed the competency. Doesn't have the same issue as updating it, as we dont need to render an updated row
     * to send to the view.
     *
     * @param TutorLanguage $tutorLanguage
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function performMockedRemove(TutorLanguage $tutorLanguage)
    {
        $request = $this->getMockedRequest([
            'pk' => $tutorLanguage->getId(),
        ]);

        // Call the Controller Update
        $controller = new TutorLanguageController();
        $controller->setContainer($this->container);

        return $controller->removeAction($request);
    }

    /**
     * Test that we can remove a TutorLanguage, and that we cant remove a non existent one.
     */
    public function testRemove()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        /** @var TutorLanguage $tutorLanguage */
        $tutorLanguage = $tutor->getTutorLanguages()->first();

        $this->performMockedRemove($tutorLanguage);

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->assertNotContains(
            $tutorLanguage,
            $tutor->getTutorLanguages()
        );

        // Now try removing it again - should throw an error
        $response = $this->performMockedRemove($tutorLanguage);

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
