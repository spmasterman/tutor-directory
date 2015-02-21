<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\PhoneManager;

class PhoneTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals('+1 123 1111111 (Home)', (string) $entityOne);
        $this->assertEquals('ONE', $entityOne->getCountry()->getThreeDigitCode());
        $this->assertTrue($entityOne->isPreferred());
    }

    /**
     * @return PhoneManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.phone');
    }
}
