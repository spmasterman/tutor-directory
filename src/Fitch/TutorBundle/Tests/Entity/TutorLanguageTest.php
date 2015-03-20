<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\TutorLanguageManagerInterface;

/**
 * Class TutorLanguageTest
 */
class TutorLanguageTest extends FixturesWebTestCase
{
    /**
     * {@inheritdoc}
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
