<?php

namespace Fitch\TutorBundle\Tests\Controller\Profile;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\OperatingRegionManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Fitch\TutorBundle\Tests\Controller\ProfileMockedUpdateTrait;

/**
 * Class ProfileControllerRegionTest.
 */
class ProfileControllerRegionTest extends FixturesWebTestCase
{
    use ProfileMockedUpdateTrait;

    /**
     * Test editing a Region
     */
    public function testUpdateRegion()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // we need a couple of countries to test with
        $regionOne = $this->getRegionManager()->findById(1);
        $regionTwo = $this->getRegionManager()->findById(2);

        // Set the region to Region One

        $tutor->setRegion($regionOne);
        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->assertEquals($regionOne, $tutor->getRegion());

        $this->performMockedUpdate($tutor, 'region', [
            'value' => $regionTwo->getId(),
        ]);

        $manager->reloadEntity($tutor);

        $this->assertEquals($regionTwo, $tutor->getRegion());
    }

    /**
     * @return TutorManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }

    /**
     * @return OperatingRegionManagerInterface
     */
    public function getRegionManager()
    {
        return $this->container->get('fitch.manager.operating_region');
    }
}
