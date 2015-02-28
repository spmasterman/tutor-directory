<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\StatusManagerInterface;

class StatusTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'Test Status One',
            (string) $entityOne
        );
    }

    /**
     * @return StatusManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.status');
    }
}
