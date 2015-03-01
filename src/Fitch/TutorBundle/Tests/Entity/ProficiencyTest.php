<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\ProficiencyManagerInterface;

class ProficiencyTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'Test Proficiency One',
            (string) $entityOne
        );
    }

    /**
     * If a color isn't specified there is a default in the entity - test its a valid
     * hex-rgb value
     */
    public function testDefaultColor()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertStringMatchesFormat(
            '#%x%x%x%x%x%x',
            $entityOne->getColor()
        );
        $this->assertContains(
            strlen($entityOne->getColor()),
            [4,7]
        );
    }

    /**
     * @return ProficiencyManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.proficiency');
    }
}
