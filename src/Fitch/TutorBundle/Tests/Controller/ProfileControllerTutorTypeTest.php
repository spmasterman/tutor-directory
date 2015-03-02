<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Fitch\TutorBundle\Model\TutorTypeManagerInterface;

/**
 * Class ProfileControllerTutorTypeTest.
 */
class ProfileControllerTutorTypeTest extends FixturesWebTestCase
{
    use ProfileMockedUpdateTrait;

    /**
     * Test editing a TutorType
     */
    public function testUpdateTutorType()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // we need a couple of countries to test with
        $tutorTypeOne = $this->getTutorTypeManager()->findById(1);
        $tutorTypeTwo = $this->getTutorTypeManager()->findById(2);

        // Set the tutorType to TutorType One

        $tutor->setTutorType($tutorTypeOne);
        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->assertEquals($tutorTypeOne, $tutor->getTutorType());

        $this->performMockedUpdate($tutor, 'tutorType', [
            'value' => $tutorTypeTwo->getId(),
        ]);

        $manager->reloadEntity($tutor);

        $this->assertEquals($tutorTypeTwo, $tutor->getTutorType());
    }

    /**
     * @return TutorManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }

    /**
     * @return TutorTypeManagerInterface
     */
    public function getTutorTypeManager()
    {
        return $this->container->get('fitch.manager.tutor_type');
    }
}
