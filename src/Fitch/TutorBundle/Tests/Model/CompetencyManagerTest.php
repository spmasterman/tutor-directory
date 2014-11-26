<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\CompetencyManager;

class CompetencyManagerTest extends FixturesWebTestCase
{

    public function testFindAll()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three entities");

        $this->assertEquals('Test Competency Type One (Test Level One)', (string)$allEntities[0]);
        $this->assertEquals('Test Competency Type Two (Test Level Two)', (string)$allEntities[1]);
        $this->assertEquals('Test Competency Type Three (Test Level Three)', (string)$allEntities[2]);
    }

    public function testFindById()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertEquals('Test Competency Type One (Test Level One)', (string)$entityOne);
    }

    public function testLifeCycle()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return 3 entities");

        // Create new one
        $newEntity = $this->getModelManager()->createCompetency();
        $newEntity
            ->setNote('note')
        ;
        $this->getModelManager()->saveCompetency($newEntity);

        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(4, $allEntities, "Should return 4 entities");
        $this->assertNotNull($allEntities[3]->getCreated());
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        $newEntity->setNote('n2');
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        sleep(1);

        $this->getModelManager()->saveCompetency($newEntity);
        $this->assertNotEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        $newEntity->setNote('n3');
        $this->getModelManager()->refreshCompetency($newEntity);
        $this->assertEquals('n2', $newEntity->getNote());

        $this->getModelManager()->removeCompetency($newEntity->getId());
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return 3 entities");
    }

    /**
     * @return CompetencyManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.competency');
    }
}
