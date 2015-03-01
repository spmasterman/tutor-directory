<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\AddressManagerInterface;
use Monolog\Handler\TestHandler;
use Monolog\Logger;

/**
 * Class AddressManagerTest.
 */
class AddressManagerTest extends FixturesWebTestCase
{
    /**  @var AddressManagerInterface */
    private $modelManager;

    /**
     * Runs for every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.address');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $allEntities = $this->modelManager->findAll();
        $this->assertCount(6, $allEntities, "Should return six entities");

        $this->assertEquals('1 Main Street', $allEntities[0]->getStreetPrimary());
        $this->assertEquals('2 Main Street', $allEntities[1]->getStreetPrimary());
        $this->assertEquals('3 Main Street', $allEntities[2]->getStreetPrimary());
        $this->assertEquals('4 Main Street', $allEntities[3]->getStreetPrimary());
        $this->assertEquals('5 Main Street', $allEntities[4]->getStreetPrimary());
        $this->assertEquals('6 Main Street', $allEntities[5]->getStreetPrimary());
    }

    /**
     *
     */
    public function testFindById()
    {
        $entityOne = $this->modelManager->findById(1);

        $this->assertEquals('1 Main Street', $entityOne->getStreetPrimary());
    }

    /**
     *
     */
    public function testLifeCycle()
    {
        // Check that there are 6 entries
        $allEntities = $this->modelManager->findAll();
        $this->assertCount(6, $allEntities, "Should return six entities");

        // Create new one
        $newEntity = $this->modelManager->createEntity();
        $newEntity
            ->setStreetPrimary('p')
            ->setStreetSecondary('s')
            ->setCity('c')
            ->setState('st')
            ->setZip('z');
        $this->modelManager->saveEntity($newEntity);

        // Check that there are 7 entries, and the new one is Timestamped correctly
        $allEntities = $this->modelManager->findAll();
        $this->assertCount(7, $allEntities, "Should return seven entities");
        $this->assertNotNull($allEntities[6]->getCreated());
        $this->assertEquals($allEntities[6]->getCreated(), $allEntities[6]->getUpdated());

        // Updated shouldn't change until persisted
        $newEntity->setStreetPrimary('p2');
        $this->assertEquals($allEntities[6]->getCreated(), $allEntities[6]->getUpdated());

        sleep(1);

        $this->modelManager->saveEntity($newEntity);
        $this->assertNotEquals($allEntities[6]->getCreated(), $allEntities[6]->getUpdated());

        // Check that when we refresh it refreshes
        $newEntity->setStreetPrimary('p3');
        $this->modelManager->reloadEntity($newEntity);
        $this->assertEquals('p2', $newEntity->getStreetPrimary());

        // Check that when we remove it, it is no longer present
        $this->modelManager->removeEntity($newEntity);
        $allEntities = $this->modelManager->findAll();
        $this->assertCount(6, $allEntities, "Should return six entities");

        /** @var Logger $logger */
        $logger = $this->container->get('logger');
        foreach ($logger->getHandlers() as $handler) {
            if ($handler instanceof TestHandler) {
                $this->assertTrue($handler->hasDebugRecords());
            }
        }
    }
}
