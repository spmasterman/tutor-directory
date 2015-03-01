<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\CompetencyType;
use Fitch\TutorBundle\Model\CompetencyTypeManagerInterface;

class CompetencyTypeManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait;

    const FIXTURE_COUNT = 3;

    /** @var  CompetencyTypeManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.competency_type');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test Competency Type One', function (CompetencyType $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Competency Type One', function (CompetencyType $entity) {
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
            function (CompetencyType $entity) {
                $entity
                    ->setName('n')
                ;
            },
            function (CompetencyType $entity) {
                $entity
                    ->setName('n2');
            },
            function (CompetencyType $entity) {
                $entity
                    ->setName('n3');
            },
            function (CompetencyType $entity) {
                return (bool) 'n2' == $entity->getName();
            }
        );
    }

    public function testFindOrCreate()
    {
        $this->performFindOrCreateTest(function (CompetencyType $entity) {
            return $entity->getName();
        });
    }
}
