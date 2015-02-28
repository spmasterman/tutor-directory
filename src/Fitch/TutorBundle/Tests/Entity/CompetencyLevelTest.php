<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\CompetencyLevelManagerInterface;

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
        $entity = $this->getModelManager()->createEntity();
        $this->assertNotNull($entity->getColor());
    }

    /**
     * @return CompetencyLevelManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.competency_level');
    }
}
