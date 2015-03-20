<?php

namespace Fitch\CommonBundle\Tests\Model;

/**
 * Trait FindModelManagerTestTrait.
 *
 * Provides some helper functions for testing ModelManagers
 */
trait FindModelManagerTestTrait
{
    /**
     * @param int      $checkCount
     * @param mixed    $checkValue
     * @param callable $checkFunction
     */
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

    /**
     * @param int      $id
     * @param mixed    $checkValue
     * @param callable $checkFunction
     */
    protected function performFindByIdTest($id, $checkValue, $checkFunction)
    {
        $entityOne = $this->modelManager->findById($id);
        $this->assertEquals($checkValue, $checkFunction($entityOne));
    }

    /**
     * @param callable $checkFunction
     */
    protected function performFindOrCreateTest($checkFunction)
    {
        $initialCount = count($this->modelManager->findAll());

        $entity = $this->modelManager->findOrCreate('TESTNEW');

        $this->assertEquals($initialCount+1, count($this->modelManager->findAll()));
        $this->assertEquals('TESTNEW', $checkFunction($entity));
    }
}
