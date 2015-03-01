<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\DefaultableModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\TutorLanguage;
use Fitch\TutorBundle\Model\LanguageManagerInterface;
use Fitch\TutorBundle\Model\TutorLanguageManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;

class TutorLanguageManagerTest  extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait,
        DefaultableModelManagerTestTrait;

    const FIXTURE_COUNT = 6;

    /** @var  TutorLanguageManagerInterface */
    protected $modelManager;

    /** @var  TutorManagerInterface */
    protected $tutorManager;

    /** @var  LanguageManagerInterface */
    protected $languageManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.tutor_language');
        $this->tutorManager = $this->container->get('fitch.manager.tutor');
        $this->languageManager = $this->container->get('fitch.manager.language');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Tutor One Speaks Language One', function (TutorLanguage $entity) {
            return $entity->getNote();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Tutor One Speaks Language One', function (TutorLanguage $entity) {
            return $entity->getNote();
        });
    }

    /**
     *
     */
    public function testLifeCycle()
    {
        $tutor = $this->tutorManager->findById(1);
        $language = $this->languageManager->findById(1);

        $this->performLifeCycleTests(
            self::FIXTURE_COUNT,
            function (TutorLanguage $entity) use ($tutor, $language) {
                $entity
                    ->setNote('n')
                    ->setLanguage($language)
                ;
                $tutor->addTutorLanguage($entity);
            },
            function (TutorLanguage $entity) {
                $entity
                    ->setNote('p2');
            },
            function (TutorLanguage $entity) {
                $entity
                    ->setNote('p3');
            },
            function (TutorLanguage $entity) {
                return (bool) 'p2' == $entity->getNote();
            }
        );
    }

    public function testFindOrCreate()
    {
        $tutor = $this->tutorManager->findById(1);

        $newTutorLanguage = $this->modelManager->findOrCreateTutorLanguage(false, $tutor);
        $this->assertContains($newTutorLanguage, $tutor->getTutorLanguages());

        $this->tutorManager->saveEntity($tutor);
        $existingTutorLanguage = $this->modelManager->findOrCreateTutorLanguage($newTutorLanguage->getId(), $tutor);
        $this->assertEquals($existingTutorLanguage, $newTutorLanguage);
    }
}
