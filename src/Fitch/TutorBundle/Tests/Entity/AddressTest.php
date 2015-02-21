<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\AddressManager;

class AddressTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            '1 Main Street, Test Address One, Test City One, Test State One 11111 Test Country One',
            (string) $entityOne
        );
    }

    public function testType()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertEquals('Home', $entityOne->getType());
        $entityOne->setType('Office');
        $this->assertEquals('Office', $entityOne->getType());
    }

    /**
     * @return AddressManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.address');
    }
}
