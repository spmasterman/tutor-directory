<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\LanguageManagerInterface;

/**
 * Class LanguageTest
 */
class LanguageTest extends FixturesWebTestCase
{
    /**
     * {@inheritdoc}
     */
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'Test Language One',
            (string) $entityOne
        );
    }

    /**
     * Test we can get and set the Tutors that speak a language
     */
    public function testTutorLanguages()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $entityTwo = $this->getModelManager()->findById(2);

        $this->assertCount(2, $entityOne->getTutorLanguages());
        $this->assertCount(2, $entityTwo->getTutorLanguages());

        $this->assertNotEquals(
            $entityOne->getTutorLanguages(),
            $entityTwo->getTutorLanguages()
        );

        $entityOne->setTutorLanguages($entityTwo->getTutorLanguages());

        $this->assertEquals(
            $entityOne->getTutorLanguages(),
            $entityTwo->getTutorLanguages()
        );

        $this->getModelManager()->reloadEntity($entityOne);
        $this->getModelManager()->reloadEntity($entityTwo);

        foreach ($entityOne->getTutorLanguages() as $tutorLanguage) {
            $entityOne->removeTutorLanguage($tutorLanguage);
        }
        $this->assertCount(0, $entityOne->getTutorLanguages());

        $this->getModelManager()->reloadEntity($entityOne);
        $this->getModelManager()->reloadEntity($entityTwo);

        foreach ($entityTwo->getTutorLanguages() as $tutorLanguage) {
            $entityOne->addTutorLanguage($tutorLanguage);
        }
        $this->assertCount(4, $entityOne->getTutorLanguages());
    }

    /**
     * @return LanguageManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.language');
    }
}
