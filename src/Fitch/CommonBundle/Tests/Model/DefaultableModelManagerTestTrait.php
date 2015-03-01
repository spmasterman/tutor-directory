<?php

namespace Fitch\CommonBundle\Tests\Model;

use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
use Monolog\Handler\TestHandler;
use Monolog\Logger;

/**
 * Trait DefaultableModelManagerTestTrait.
 *
 * Provides some helper functions for testing ModelManagers for Entities that
 * implement DefaultTraitInterface
 */
trait DefaultableModelManagerTestTrait
{

    public function performFindDefaultTest($instanceFunction)
    {
        $entity = $this->modelManager->findDefaultEntity();
        $this->assertTrue($instanceFunction($entity));
        $this->assertTrue($entity->isDefault());
    }
}
