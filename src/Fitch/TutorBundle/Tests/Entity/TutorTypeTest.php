<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\TutorTypeManagerInterface;

class TutorTypeTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'Test Tutor Type One',
            (string) $entityOne
        );
    }

    /**
     * @return TutorTypeManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor_type');
    }
}
