<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\BusinessAreaManagerInterface;

/**
 * Class BusinessAreaTest
 */
class BusinessAreaTest extends FixturesWebTestCase
{
    /**
     * Tests the magic __toString
     */
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'Test Business Area 1',
            (string) $entityOne
        );

        $entityOne->setDisplayAsCode(true);

        $this->assertEquals(
            'ABC',
            (string) $entityOne
        );

    }

    /**
     * @return BusinessAreaManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.business_area');
    }
}
