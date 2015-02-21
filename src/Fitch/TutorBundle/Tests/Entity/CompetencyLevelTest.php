<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\CompetencyLevelManager;

class CompetencyLevelTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'Test Level One',
            (string) $entityOne
        );
    }

    public function testDefaultValues()
    {
        $entity = $this->getModelManager()->createCompetencyLevel();
        $this->assertNotNull($entity->getColor());
    }

    /**
     * @return CompetencyLevelManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.competency_level');
    }
}
