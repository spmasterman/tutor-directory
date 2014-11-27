<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\StatusManager;
use Fitch\TutorBundle\Model\TutorTypeManager;

class TutorTypeTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'Test Tutor Type One',
            (string)$entityOne
        );
    }

    /**
     * @return TutorTypeManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor_type');
    }
}
