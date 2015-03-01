<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Entity\DefaultTraitInterface;
use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\DefaultableModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\BusinessArea;
use Fitch\TutorBundle\Model\BusinessAreaManagerInterface;

/**
 * Class BusinessAreaManagerTest.
 */
class BusinessAreaManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        DefaultableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait;

    const FIXTURE_COUNT = 4;

    /** @var  BusinessAreaManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.business_area');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test Business Area 1', function (BusinessArea $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Business Area 1', function (BusinessArea $entity) {
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
            function (BusinessArea $entity) {
                $entity
                    ->setName('b')
                    ->setCode('c')
                    ->setPrependToCategoryName(false)
                    ->setDisplayAsCode(false)
                    ->setDefault(false);
            },
            function (BusinessArea $entity) {
                $entity
                    ->setName('p2');
            },
            function (BusinessArea $entity) {
                $entity
                    ->setName('p3');
            },
            function (BusinessArea $entity) {
                return (bool) 'p2' == $entity->getName();
            }
        );
    }

    public function testBuildChoices()
    {
        $this->performBuildChoicesTest(
            self::FIXTURE_COUNT,
            function ($entity) {
                return $entity instanceof BusinessArea;
            }
        );
    }

    public function testBuildGroupedChoices()
    {
        $choices = $this->modelManager->buildGroupedChoices();
        $this->assertCount(self::FIXTURE_COUNT, $choices);
        // this is not *generally* true only if the manager uses Flatlist
    }

    public function testFindDefault()
    {
        $this->performFindDefaultTest(
            function ($entity) {
                return $entity instanceof BusinessArea;
            },
            function (DefaultTraitInterface $entity) {
                return $entity->isDefault();
            }
        );
    }
}
