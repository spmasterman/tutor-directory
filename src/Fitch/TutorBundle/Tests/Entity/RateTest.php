<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\RateManagerInterface;

class RateTest extends FixturesWebTestCase
{
    public function testNameAndAmount()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals('Test Rate One', $entityOne->getName());
        $this->assertEquals(1000, $entityOne->getAmount());
    }

    /**
     * @return RateManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.rate');
    }
}
