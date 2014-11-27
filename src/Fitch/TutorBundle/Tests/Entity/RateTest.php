<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\RateManager;

class RateTest extends FixturesWebTestCase
{
    public function testNameAndAmount()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals('Test Rate One', $entityOne->getName());
        $this->assertEquals(1000 , $entityOne->getAmount());
    }

    /**
     * @return RateManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.rate');
    }
}
