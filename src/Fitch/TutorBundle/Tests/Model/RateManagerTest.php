<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\RateManager;

class RateManagerTest extends FixturesWebTestCase
{

    public function testFindAll()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return six entities");

        $this->assertEquals('Test Rate One', $allEntities[0]->getName());
        $this->assertEquals('Test Rate Two', $allEntities[1]->getName());
        $this->assertEquals('Test Rate Three', $allEntities[2]->getName());
    }

    public function testFindById()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertEquals('Test Rate One', $entityOne->getName());
    }

    public function testLifeCycle()
    {
        // Check that there are 6 entries
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return six entities");

        // Create new one
        $newEntity = $this->getModelManager()->createRate();
        $newEntity
            ->setName('n')
            ->setAmount(1)
        ;
        $this->getModelManager()->saveRate($newEntity);

        // Check that there are 4 entries, and the new one is Timestamped correctly
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(4, $allEntities, "Should return seven entities");
        $this->assertNotNull($allEntities[3]->getCreated());
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        // Updated shouldn't change until persisted
        $newEntity->setName('n2');
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        sleep(1);

        $this->getModelManager()->saveRate($newEntity);
        $this->assertNotEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        // Check that when we refresh it refreshes
        $newEntity->setName('n3');
        $this->getModelManager()->refreshRate($newEntity);
        $this->assertEquals('n2', $newEntity->getName());

        // Check Logs:
        $logs = $this->getModelManager()->getLogs($newEntity);
        $this->assertCount(2, $logs);
        $this->assertInstanceOf('Gedmo\Loggable\Entity\LogEntry', $logs[0]);
        $this->assertInstanceOf('Gedmo\Loggable\Entity\LogEntry', $logs[1]);

        $this->assertEquals(2, $logs[0]->getVersion());
        $this->assertEquals('update', $logs[0]->getAction());
        $this->assertEquals(['name' => 'n2'], $logs[0]->getData());

        $this->assertEquals(1, $logs[1]->getVersion());
        $this->assertEquals('create', $logs[1]->getAction());
        $this->assertEquals(['name' => 'n', 'amount' => 1], $logs[1]->getData());

        // Check that when we remove it, it is no longer present
        $this->getModelManager()->removeRate($newEntity->getId());

        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return six entities");
    }

    /**
     * @return RateManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.rate');
    }
}
