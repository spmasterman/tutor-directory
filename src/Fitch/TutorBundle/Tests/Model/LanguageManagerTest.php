<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\Language;
use Fitch\TutorBundle\Model\LanguageManagerInterface;

class LanguageManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait;

    const FIXTURE_COUNT = 4;
    const ACTIVE_FIXTURE_COUNT = 2;
    const PREFERRED_FIXTURE_COUNT = 2;
    const ACTIVE_AND_PREFERRED_COUNT = 1;

    /** @var  LanguageManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.language');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test Language One', function (Language $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Language One', function (Language $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testLifeCycle()
    {
        $this->performLifeCycleTests(
            self::FIXTURE_COUNT,
            function (Language $entity) {
                $entity
                    ->setName('n')
                    ->setActive(true)
                    ->setPreferred(true)
                    ->setThreeLetterCode('abc')
                ;
            },
            function (Language $entity) {
                $entity
                    ->setName('n2');
            },
            function (Language $entity) {
                $entity
                    ->setName('n3');
            },
            function (Language $entity) {
                return (bool) 'n2' == $entity->getName();
            }
        );
    }

    /**
     *
     */
    public function testFindAllSorted()
    {
        $sorted = $this->modelManager->findAllSorted();

        $index = 0;
        foreach ($sorted as $entity) {
            if ($index < self::PREFERRED_FIXTURE_COUNT) {
                $this->assertTrue($entity->isPreferred(), 'Unexpected Not-Preferred Entity');
            } else {
                $this->assertFalse($entity->isPreferred(), 'Unexpected Preferred Entity');
            }

            $index++;
        }
    }

    /**
     *
     */
    public function testBuildChoices()
    {
        $this->performBuildChoicesTest(
            self::ACTIVE_FIXTURE_COUNT,
            function ($entity) {
                return $entity instanceof Language;
            },
            function (Language $entity) {
                return $entity->isActive();
            }
        );
    }

    /**
     *
     */
    public function testBuildPreferredChoices()
    {
        $this->performBuildPreferredChoicesTest(
            self::ACTIVE_AND_PREFERRED_COUNT,
            function ($entity) {
                return $entity instanceof Language;
            }
        );
    }

    /**
     *
     */
    public function testFindOrCreate()
    {
        $newLanguage = $this->modelManager->findOrCreate('NEWLANGUAGE');
        $this->assertEquals('NEWLANGUAGE', $newLanguage->getName());

        $this->modelManager->saveEntity($newLanguage);

        $existingLanguage = $this->modelManager->findOrCreate('NEWLANGUAGE');

        $this->assertEquals($existingLanguage->getId(), $newLanguage->getId());
    }
}
