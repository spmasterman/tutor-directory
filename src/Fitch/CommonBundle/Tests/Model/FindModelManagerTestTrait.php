<?php

namespace Fitch\CommonBundle\Tests\Model;

use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
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
}
