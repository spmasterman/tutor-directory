<?php

namespace Fitch\CommonBundle\Tests\Model;

use Fitch\CommonBundle\Entity\TimestampableEntityInterface;
use Monolog\Handler\TestHandler;
use Monolog\Logger;

/**
 * Class TimestampableModelManagerTestTrait.
 *
 * Provides some helper functions for testing ModelManagers for Entities that
 * implement TimestampableEntityInterface
 */
trait TimestampableModelManagerTestTrait
{
    protected function performLifeCycleTests(
        $fixtureCount,
        $createFunction,
        $editFunction,
        $uncommittedEditFunction,
        $checkFunction
    ) {
        // Check that there are 1 entry
        $entities = $this->modelManager->findAll();

        $this->assertTrue(
            $entities[0] instanceof TimestampableEntityInterface,
            "Test case not suitable for Subject under test"
        );
        /* @var TimestampableEntityInterface[] $entities */

        $this->assertCount(
            $fixtureCount,
            $entities,
            sprintf("Should return %s entity(s)", $fixtureCount)
        );

        // Create new one
        $newEntity = $this->modelManager->createEntity();

        $createFunction($newEntity);
        $this->modelManager->saveEntity($newEntity);

        // Check that there are 7 entries, and the new one is Timestamped correctly
        $entities = $this->modelManager->findAll();

        $this->assertCount(
            $fixtureCount + 1,
            $entities,
            sprintf("Should return %s entity(s)", $fixtureCount + 1)
        );

        $this->assertNotNull($entities[$fixtureCount]->getCreated());
        $this->assertEquals($entities[$fixtureCount]->getCreated(), $entities[$fixtureCount]->getUpdated());

        // Updated shouldn't change until persisted
        $editFunction($newEntity);

        $this->assertEquals($entities[$fixtureCount]->getCreated(), $entities[$fixtureCount]->getUpdated());

        sleep(2);

        $this->modelManager->saveEntity($newEntity);
        $this->assertNotEquals($entities[$fixtureCount]->getCreated(), $entities[$fixtureCount]->getUpdated());

        // Check that when we refresh it refreshes
        $uncommittedEditFunction($newEntity);
        $this->modelManager->reloadEntity($newEntity);
        $this->assertTrue($checkFunction($newEntity));

        // Check that when we remove it, it is no longer present
        $this->modelManager->removeEntity($newEntity);
        $entities = $this->modelManager->findAll();

        $this->assertCount(
            $fixtureCount,
            $entities,
            sprintf("Should return %s entity(s)", $fixtureCount)
        );

        /** @var Logger $logger */
        $logger = $this->container->get('logger');
        foreach ($logger->getHandlers() as $handler) {
            if ($handler instanceof TestHandler) {
                $this->assertTrue($handler->hasDebugRecords());
            }
        }
    }
}
