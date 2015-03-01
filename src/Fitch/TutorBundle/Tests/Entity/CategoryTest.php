<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\BusinessAreaManagerInterface;
use Fitch\TutorBundle\Model\CategoryManagerInterface;

/**
 * Class CategoryTest
 */
class CategoryTest extends FixturesWebTestCase
{
    /**
     * Tests the magic __toString
     */
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $businessArea = $entityOne->getBusinessArea();
        $businessArea->setPrependToCategoryName(false);

        $this->assertEquals(
            'Test Category One',
            (string) $entityOne
        );

        $businessArea->setPrependToCategoryName(true);
        $businessArea->setName('NAME');
        $businessArea->setCode('CODE');
        $businessArea->setDisplayAsCode(false);

        $this->assertEquals(
            '(CODE) NAME: Test Category One',
            (string) $entityOne
        );

        $businessArea->setDisplayAsCode(true);

        $this->assertEquals(
            'CODE: Test Category One',
            (string) $entityOne
        );

    }

    /**
     * @return CategoryManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.category');
    }

    /**
     * @return BusinessAreaManagerInterface
     */
    public function getBusinessAreaManager()
    {
        return $this->container->get('fitch.manager.business_area');
    }
}
