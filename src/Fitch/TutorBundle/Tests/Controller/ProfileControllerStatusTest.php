<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\StatusManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;

/**
 * Class ProfileControllerStatusTest.
 */
class ProfileControllerStatusTest extends FixturesWebTestCase
{
    use ProfileMockedUpdateTrait;

    /**
     * Test editing a Status
     */
    public function testUpdateStatus()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // we need a couple of countries to test with
        $statusOne = $this->getStatusManager()->findById(1);
        $statusTwo = $this->getStatusManager()->findById(2);

        // Set the status to Status One

        $tutor->setStatus($statusOne);
        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->assertEquals($statusOne, $tutor->getStatus());

        $this->performMockedUpdate($tutor, 'status', [
            'value' => $statusTwo->getId(),
        ]);

        $manager->reloadEntity($tutor);

        $this->assertEquals($statusTwo, $tutor->getStatus());
    }

    /**
     * @return TutorManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }

    /**
     * @return StatusManagerInterface
     */
    public function getStatusManager()
    {
        return $this->container->get('fitch.manager.status');
    }
}
