<?php

namespace Fitch\CommonBundle\Tests\Model;

use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
use Monolog\Handler\TestHandler;
use Monolog\Logger;

/**
 * Trait ChoicesModelManagerTestTrait
 *
 * Provides some helper functions for testing ModelManagers for Entities that
 * implement DefaultTraitInterface
 */
trait ChoicesModelManagerTestTrait
{

    public function performBuildChoicesTest($expectedCount, $instanceFunction)
    {
        $choices = $this->modelManager->buildChoices();

        $this->assertCount($expectedCount, $choices);

        foreach ($choices as $choice) {
            $this->assertTrue($instanceFunction($choice));
        }
    }
}
