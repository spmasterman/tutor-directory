<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\CompetencyTypeManagerInterface;

class CompetencyTypeTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'Test Competency Type One',
            (string) $entityOne
        );
    }

    /**
     * @return CompetencyTypeManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.competency_type');
    }
}
