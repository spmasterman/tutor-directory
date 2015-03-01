<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\LanguageManagerInterface;

class LanguageTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'Test Language One',
            (string) $entityOne
        );
    }

    /**
     * @return LanguageManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.language');
    }
}
