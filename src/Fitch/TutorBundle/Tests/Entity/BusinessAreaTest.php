<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\BusinessAreaManagerInterface;

/**
 * Class BusinessAreaTest.
 */
class BusinessAreaTest extends FixturesWebTestCase
{
    /**
     * Tests the magic __toString.
     */
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            '(ONE) Test Business Area 1',
            (string) $entityOne
        );

        $entityOne->setDisplayAsCode(true);

        $this->assertEquals(
            'ONE',
            (string) $entityOne
        );
    }

    /**
     * Test the Categories Access methods, others get tested in the CRUD Controller Tests
     */
    public function testCategories()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $entityTwo = $this->getModelManager()->findById(2);

        $this->assertCount(1, $entityOne->getCategories());
        $this->assertCount(1, $entityTwo->getCategories());

        $this->assertNotEquals(
            $entityOne->getCategories(),
            $entityTwo->getCategories()
        );

        $entityOne->setCategories($entityTwo->getCategories());

        $this->assertEquals(
            $entityOne->getCategories(),
            $entityTwo->getCategories()
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
