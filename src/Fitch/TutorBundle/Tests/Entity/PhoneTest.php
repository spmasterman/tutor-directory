<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\PhoneManagerInterface;

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
     * @return PhoneManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.phone');
    }
}
