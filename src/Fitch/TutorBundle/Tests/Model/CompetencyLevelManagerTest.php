<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\DefaultableModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\CompetencyLevel;
use Fitch\TutorBundle\Model\CompetencyLevelManagerInterface;

class CompetencyLevelManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait;

    const FIXTURE_COUNT = 3;

    /** @var  CompetencyLevelManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.competency_level');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test Level One', function (CompetencyLevel $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Level One', function (CompetencyLevel $entity) {
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
            function (CompetencyLevel $entity) {
                $entity
                    ->setName('n')
                    ->setColor('#334455')
                    ;
            },
            function (CompetencyLevel $entity) {
                $entity
                    ->setName('p2');
            },
            function (CompetencyLevel $entity) {
                $entity
                    ->setName('p3');
            },
            function (CompetencyLevel $entity) {
                return (bool) 'p2' == $entity->getName();
            }
        );
    }

    public function testFindOrCreate()
    {
        $this->performFindOrCreateTest(function (CompetencyLevel $entity) {
            return $entity->getName();
        });
    }
}
