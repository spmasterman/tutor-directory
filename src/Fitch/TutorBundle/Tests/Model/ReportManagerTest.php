<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\Report;
use Fitch\TutorBundle\Model\ReportManagerInterface;

/**
 * Class ReportManagerTest.
 */
class ReportManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait;

    const FIXTURE_COUNT = 1;

    /** @var  ReportManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.report');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test Report One', function (Report $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Report One', function (Report $entity) {
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
            function (Report $entity) {
                $entity
                    ->setName('n')
                    ->setDefinition('')
                ;
            },
            function (Report $entity) {
                $entity
                    ->setName('p2');
            },
            function (Report $entity) {
                $entity
                    ->setName('p3');
            },
            function (Report $entity) {
                return (bool) 'p2' == $entity->getName();
            }
        );
    }
}
