<?php

namespace Fitch\CommonBundle\Tests\Model;

use Fitch\CommonBundle\Entity\TimestampableEntityInterface;
use Monolog\Handler\TestHandler;
use Monolog\Logger;

/**
 * Class FindModelManagerTestTrait.
 *
 * Provides some helper functions for testing ModelManagers
 */
trait FindModelManagerTestTrait
{
    protected function performFindAllTest($checkCount, $checkValue, $checkFunction)
    {
        $entities = $this->modelManager->findAll();

        $this->assertCount(
            $checkCount,
            $entities,
            sprintf("Should return %s entity(s)", $checkCount)
        );

        $this->assertEquals($checkValue, $checkFunction($entities[0]));
    }

    protected function performFindByIdTest($id, $checkValue, $checkFunction)
    {
        $entityOne = $this->modelManager->findById($id);
        $this->assertEquals($checkValue, $checkFunction($entityOne));
    }

    protected function performFindOrCreateTest($checkFunction)
    {
        $initialCount = count($this->modelManager->findAll());

        $entity = $this->modelManager->findOrCreate('TESTNEW');

        $this->assertEquals($initialCount+1, count($this->modelManager->findAll()));
        $this->assertEquals('TESTNEW', $checkFunction($entity));
    }
}
