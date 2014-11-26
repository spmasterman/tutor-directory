<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Model\CompetencyManager;

class CompetencyTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'Test Competency Type One (Test Level One)',
            (string)$entityOne
        );

        $entityOne
            ->setCompetencyLevel(null)
            ->setCompetencyType(null)
        ;

        $this->assertEquals(
            Competency::NOT_YET_SPECIFIED . ' (' . Competency::NOT_YET_SPECIFIED .')',
            (string)$entityOne
        );

        // As the kernel isn't rebooted between tests this can bleed-over into subsequent tests if we dont reset it
        $this->getModelManager()->refreshCompetency($entityOne);
    }

    /**
     * @return CompetencyManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.competency');
    }
}
