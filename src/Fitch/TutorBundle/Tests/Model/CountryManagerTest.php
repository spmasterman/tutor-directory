<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\DefaultableModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\Country;
use Fitch\TutorBundle\Model\CountryManagerInterface;

class CountryManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        DefaultableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait;

    const FIXTURE_COUNT = 4;
    const ACTIVE_FIXTURE_COUNT = 3;
    const PREFERRED_FIXTURE_COUNT = 2;

    /** @var  CountryManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.country');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test Country One', function (Country $entity) {
            return $entity->getName();
        });
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
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Country One', function (Country $entity) {

            $this->assertEquals('Test Region One', $entity->getDefaultRegion()->getName());

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
            function (Country $entity) {
                $entity
                    ->setName('c')
                    ->setDefaultRegion(null)
                    ->setDialingCode('dc')
                    ->setPreferred(true)
                    ->setThreeDigitCode('123')
                    ->setTwoDigitCode('12')
                    ->setActive(false);
            },
            function (Country $entity) {
                $entity->setName('p2');
            },
            function (Country $entity) {
                $entity->setName('p3');
            },
            function (Country $entity) {
                return (bool) 'p2' == $entity->getName();
            }
        );
    }

    public function testBuildChoices()
    {
        $this->performBuildChoicesTest(
            self::ACTIVE_FIXTURE_COUNT,
            function ($entity) {
                return $entity instanceof Country;
            },
            function (Country $entity) {
                return $entity->isActive();
            }
        );
    }

    public function testBuildPreferredChoices()
    {
        $this->performBuildPreferredChoicesTest(
            self::PREFERRED_FIXTURE_COUNT,
            function ($entity) {
                return $entity instanceof Country;
            },
            function (Country $entity) {
                return $entity->isPreferred();
            }
        );
    }

    public function testFindDefault()
    {
        $this->performFindDefaultTest(
            function ($entity) {
                return $entity instanceof Country;
            },
            function (Country $entity) {
                return $entity->getTwoDigitCode() == 'GB';
            }
        );

        $defaultEntity = $this->modelManager->findDefaultEntity();
        $defaultEntity->setTwoDigitCode('XX');
        $this->modelManager->saveEntity($defaultEntity);

        $this->performFindDefaultTest(
            function ($entity) {
                return is_null($entity);
            },
            function ($entity) {
                return is_null($entity);
            }
        );
    }
}
