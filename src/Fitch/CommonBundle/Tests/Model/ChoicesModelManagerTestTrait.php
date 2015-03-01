<?php

namespace Fitch\CommonBundle\Tests\Model;

/**
 * Trait ChoicesModelManagerTestTrait.
 *
 * Provides some helper functions for testing ModelManagers for Entities that
 * implement DefaultTraitInterface
 */
trait ChoicesModelManagerTestTrait
{
    /**
     * @param int           $expectedCount
     * @param callable      $instanceFunction
     * @param callable|bool $checkFunction
     */
    public function performBuildChoicesTest($expectedCount, $instanceFunction, $checkFunction = false)
    {
        $choices = $this->modelManager->buildChoices();
        $this->assertCount($expectedCount, $choices);
        $this->checkChoices($choices, $instanceFunction, $checkFunction);
    }

    /**
     * @param int           $expectedCount
     * @param callable      $instanceFunction
     * @param callable|bool $checkFunction
     */
    public function performBuildPreferredChoicesTest($expectedCount, $instanceFunction, $checkFunction = false)
    {
        $choices = $this->modelManager->buildPreferredChoices();
        $this->assertCount($expectedCount, $choices);
        $this->checkChoices($choices, $instanceFunction, $checkFunction);
    }

    /**
     * @param array         $choices
     * @param callable      $instanceFunction
     * @param callable|bool $checkFunction
     */
    private function checkChoices($choices, $instanceFunction, $checkFunction)
    {
        foreach ($choices as $choice) {
            $this->assertTrue($instanceFunction($choice));
        }

        if ($checkFunction) {
            foreach ($this->modelManager->findAll() as $entity) {
                if (in_array($entity, $choices)) {
                    $this->assertTrue($checkFunction($entity));
                } else {
                    $this->assertFalse($checkFunction($entity));
                }
            }
        }
    }
}
