<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\OperatingRegionManager;

class OperatingRegionTest extends FixturesWebTestCase
{
    public function testFindAll()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three entities");

        $this->assertEquals('Test Region One', (string) $allEntities[0]);
        $this->assertEquals('Test Region Two', (string) $allEntities[1]);
        $this->assertEquals('Test Region Three', (string) $allEntities[2]);
    }

    public function testFindById()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertEquals('Test Region One', (string) $entityOne);
    }

    public function testFindDefaultOperatingRegion()
    {
        $entity = $this->getModelManager()->findDefaultOperatingRegion();
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals($entity, $entityOne);
        $this->assertTrue($entity->isDefault());
    }

    public function testLifeCycle()
    {
        // Check that there are 3 entries
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three entities");

        // Create new one
        $newEntity = $this->getModelManager()->createOperatingRegion();
        $newEntity
            ->setCode('c')
            ->setName('n')
            ->setDefault(false)
        ;
        $this->getModelManager()->saveOperatingRegion($newEntity);

        // Check that there are 7 entries, and the new one is Timestamped correctly
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(4, $allEntities, "Should return 4 entities");
        $this->assertNotNull($allEntities[3]->getCreated());
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        // Updated shouldn't change until persisted
        $newEntity->setName('n2');
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        sleep(1);

        $this->getModelManager()->saveOperatingRegion($newEntity);
        $this->assertNotEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        // Check that when we refresh it refreshes
        $newEntity->setName('n3');
        $this->getModelManager()->refreshOperatingRegion($newEntity);
        $this->assertEquals('n2', $newEntity->getName());

        // Check that when we remove it, it is no longer present
        $this->getModelManager()->removeOperatingRegion($newEntity->getId());
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three entities");
    }

    /**
     * @return OperatingRegionManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.operating_region');
    }
}
