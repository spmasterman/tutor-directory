<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\TutorLanguageManagerInterface;

class TutorLanguageTest extends FixturesWebTestCase
{
    /**
     *
     */
    public function testName()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals('Tutor One Speaks Language One', $entityOne->getNote());
    }

    /**
     * @return TutorLanguageManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor_language');
    }
}
