<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\OperatingRegionManagerInterface;

class OperatingRegionTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'Test Region One',
            (string) $entityOne
        );
        $this->assertEquals('ONE', $entityOne->getDefaultCurrency()->getThreeDigitCode());
        $this->assertEquals('ONE', $entityOne->getCode());

        $entityOne->setDefaultCurrency(null);
        $this->getModelManager()->saveEntity($entityOne);
        $this->getModelManager()->reloadEntity($entityOne);

        $this->assertNull($entityOne->getDefaultCurrency());
    }

    /**
     * @return OperatingRegionManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.operating_region');
    }
}
