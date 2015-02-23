<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\TutorManager;

class TutorManagerTest extends FixturesWebTestCase
{
    public function testFindAll()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three file types");

        $this->assertEquals('Test Tutor One', $allEntities[0]->getName());
        $this->assertEquals('Test Tutor Two', $allEntities[1]->getName());
        $this->assertEquals('Test Tutor Three', $allEntities[2]->getName());
    }

    public function testFindById()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertEquals('Test Tutor One', $entityOne->getName());
    }

    public function testLifeCycle()
    {
        // Check that there are 3 entries
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three entities");

        // Create new one - these services probably should all be stubbed/mocked to test this in isolation ??
        $newEntity = $this->getModelManager()->createTutor(
            $this->container->get('fitch.manager.address'),
            $this->container->get('fitch.manager.country'),
            $this->container->get('fitch.manager.status'),
            $this->container->get('fitch.manager.operating_region'),
            $this->container->get('fitch.manager.tutor_type')
        );

        $newEntity
            ->setName('t')
            ->setBio('b')
            ->setLinkedInURL('l')
        ;
        $this->getModelManager()->saveTutor($newEntity);

        // Check that there are 4 entries, and the new one is Timestamped correctly
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(4, $allEntities, "Should return four entities");
        $this->assertNotNull($allEntities[3]->getCreated());
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        // Updated shouldn't change until persisted
        $newEntity->setName('t2');
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        sleep(1);

        $this->getModelManager()->saveTutor($newEntity);
        $this->assertNotEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        // Check that when we refresh it refreshes
        $newEntity->setName('t3');
        $this->getModelManager()->refreshTutor($newEntity);
        $this->assertEquals('t2', $newEntity->getName());

        // Check that when we remove it, it is no longer present
        $this->getModelManager()->removeTutor($newEntity->getId());
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three entities");
    }

    /**
     * @return TutorManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }
}
