<?php

namespace Fitch\TutorBundle\Tests\Model;

use Doctrine\ORM\EntityNotFoundException;
use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\CompetencyLevelManager;

class CompetencyLevelManagerTest extends FixturesWebTestCase
{
    public function testFindAll()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three entities");

        $this->assertEquals('Test Level One', $allEntities[0]->getName());
        $this->assertEquals('Test Level Two', $allEntities[1]->getName());
        $this->assertEquals('Test Level Three', $allEntities[2]->getName());
    }

    public function testFindById()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertEquals('Test Level One', $entityOne->getName());
    }

    public function testFindOrCreated()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three entities");

        $existingEntity = $this->getModelManager()->findOrCreate($allEntities[0]->getName());
        $this->assertEquals($existingEntity, $allEntities[0]);

        $newEntity = $this->getModelManager()->findOrCreate('c-new');
        $allEntities = $this->getModelManager()->findAll();
        $this->assertContains($newEntity, $allEntities);
        $this->assertCount(4, $allEntities, "Should return three entities");
    }

    public function testLifeCycle()
    {
        // Check that there are 6 entries
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return 3 entities");

        // Create new one
        $newEntity = $this->getModelManager()->createCompetencyLevel();
        $newEntity
            ->setName('n')
            ->setColor('#334455')
        ;
        $this->getModelManager()->saveCompetencyLevel($newEntity);

        // Check that there are 7 entries, and the new one is Timestamped correctly
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(4, $allEntities, "Should return 4 entities");
        $this->assertNotNull($allEntities[3]->getCreated());
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        // Updated shouldn't change until persisted
        $newEntity->setName('n2');
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        sleep(1);

        $this->getModelManager()->saveCompetencyLevel($newEntity);
        $this->assertNotEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        // Check that when we refresh it refreshes
        $newEntity->setName('n3');
        $this->getModelManager()->refreshCompetencyLevel($newEntity);
        $this->assertEquals('n2', $newEntity->getName());

        // Check that when we remove it, it is no longer present
        $this->getModelManager()->removeCompetencyLevel($newEntity->getId());
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return 3 entities");
    }

    /**
     * @expectedException \Fitch\CommonBundle\Exception\EntityNotFoundException
     */
    public function testFindOrCreate()
    {
        $entity = $this->getModelManager()->findOrCreate('Test Level One');
        $this->assertEquals(1, $entity->getId());

        $shouldBeNull = $this->getModelManager()->findById(4);
        $this->assertNull($shouldBeNull);

        $entity = $this->getModelManager()->findOrCreate('Test New');
        $this->assertEquals(4, $entity->getId());
    }

    /**
     * @return CompetencyLevelManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.competency_level');
    }
}
