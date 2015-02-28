<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\CompetencyTypeManager;

class CompetencyTypeManagerTest extends FixturesWebTestCase
{
    public function testFindAll()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three entities");

        $this->assertEquals('Test Competency Type One', $allEntities[0]->getName());
        $this->assertEquals('Test Competency Type Two', $allEntities[1]->getName());
        $this->assertEquals('Test Competency Type Three', $allEntities[2]->getName());
    }

    public function testFindById()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertEquals('Test Competency Type One', $entityOne->getName());
    }

    public function testFindOrCreated()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three entities");

        $existingEntity = $this->getModelManager()->findOrCreate(
            $allEntities[0]->getName(),
            $this->container->get('fitch.manager.category')
        );
        $this->assertEquals($existingEntity, $allEntities[0]);

        $newEntity = $this->getModelManager()->findOrCreate('c-new', $this->container->get('fitch.manager.category'));
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
        $newEntity = $this->getModelManager()->createCompetencyType(
            $this->container->get('fitch.manager.category')
        );
        $newEntity
            ->setName('n')
        ;
        $this->getModelManager()->saveCompetencyType($newEntity);

        // Check that there are 7 entries, and the new one is Timestamped correctly
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(4, $allEntities, "Should return 4 entities");
        $this->assertNotNull($allEntities[3]->getCreated());
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        // Updated shouldn't change until persisted
        $newEntity->setName('n2');
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        sleep(1);

        $this->getModelManager()->saveCompetencyType($newEntity);
        $this->assertNotEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        // Check that when we refresh it refreshes
        $newEntity->setName('n3');
        $this->getModelManager()->reloadEntity($newEntity);
        $this->assertEquals('n2', $newEntity->getName());

        // Check that when we remove it, it is no longer present
        $this->getModelManager()->removeEntity($newEntity->getId());
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return 3 entities");
    }

    /**
     * @expectedException \Fitch\CommonBundle\Exception\EntityNotFoundException
     */
    public function testFindOrCreate()
    {
        $entity = $this->getModelManager()->findOrCreate(
            'Test Competency Type One',
            $this->container->get('fitch.manager.category')
        );
        $this->assertEquals(1, $entity->getId());

        $shouldBeNull = $this->getModelManager()->findById(4);
        $this->assertNull($shouldBeNull);

        $entity = $this->getModelManager()->findOrCreate(
            'Test New',
            $this->container->get('fitch.manager.category')
        );
        $this->assertEquals(4, $entity->getId());
    }

    /**
     * @return CompetencyTypeManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.competency_type');
    }
}
