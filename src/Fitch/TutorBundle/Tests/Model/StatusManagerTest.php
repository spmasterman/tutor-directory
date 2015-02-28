<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\StatusManager;

class StatusManagerTest extends FixturesWebTestCase
{
    public function testFindAll()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three file types");

        $this->assertEquals('Test Status One', $allEntities[0]->getName());
        $this->assertEquals('Test Status Two', $allEntities[1]->getName());
        $this->assertEquals('Test Status Three', $allEntities[2]->getName());
    }

    public function testFindById()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertEquals('Test Status One', $entityOne->getName());
    }

    public function testLifeCycle()
    {
        // Check that there are 3 entries
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three entities");

        // Create new one
        $newEntity = $this->getModelManager()->createEntity();
        $newEntity
            ->setName('t')
            ->setDefault(false)
        ;
        $this->getModelManager()->saveStatus($newEntity);

        // Check that there are 4 entries, and the new one is Timestamped correctly
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(4, $allEntities, "Should return four entities");
        $this->assertNotNull($allEntities[3]->getCreated());
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        // Updated shouldn't change until persisted
        $newEntity->setName('t2');
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        sleep(1);

        $this->getModelManager()->saveStatus($newEntity);
        $this->assertNotEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        // Check that when we refresh it refreshes
        $newEntity->setName('t3');
        $this->getModelManager()->reloadEntity($newEntity);
        $this->assertEquals('t2', $newEntity->getName());

        // Check that when we remove it, it is no longer present
        $this->getModelManager()->removeEntity($newEntity->getId());
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three entities");
    }

    public function testFindDefaultFileType()
    {
        $entity = $this->getModelManager()->findDefaultStatus();

        $this->assertTrue($entity->isDefault());
    }

    /**
     * @return StatusManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.status');
    }
}
