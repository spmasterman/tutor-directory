<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\OperatingRegionManager;

class OperatingRegionTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'Test Region One',
            (string)$entityOne
        );
        $this->assertEquals('ONE', $entityOne->getDefaultCurrency()->getThreeDigitCode());
        $this->assertEquals('ONE', $entityOne->getCode());

        $entityOne->setDefaultCurrency(null);
        $this->getModelManager()->saveOperatingRegion($entityOne);
        $this->getModelManager()->refreshOperatingRegion($entityOne);

        $this->assertNull($entityOne->getDefaultCurrency());
    }

    /**
     * @return OperatingRegionManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.operating_region');
    }
}
