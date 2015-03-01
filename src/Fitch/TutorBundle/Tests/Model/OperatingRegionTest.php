<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\DefaultableModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\OperatingRegion;
use Fitch\TutorBundle\Model\OperatingRegionManagerInterface;

class OperatingRegionTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait,
        DefaultableModelManagerTestTrait;

    const FIXTURE_COUNT = 3;

    /** @var  OperatingRegionManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.operating_region');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test Region One', function (OperatingRegion $entity) {
            return (string) $entity;
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Region One', function (OperatingRegion $entity) {
            return (string) $entity;
        });
    }

    /**
     *
     */
    public function testLifeCycle()
    {
        $this->performLifeCycleTests(
            self::FIXTURE_COUNT,
            function (OperatingRegion $entity) {
                $entity
                    ->setName('n')
                    ->setCode('c')
                    ->setDefault(false)
                ;
            },
            function (OperatingRegion $entity) {
                $entity
                    ->setName('p2');
            },
            function (OperatingRegion $entity) {
                $entity
                    ->setName('p3');
            },
            function (OperatingRegion $entity) {
                return (bool) 'p2' == $entity->getName();
            }
        );
    }

    /**
     *
     */
    public function testFindDefault()
    {
        // Should work
        $this->performFindDefaultTest(
            function ($entity) {
                return $entity instanceof OperatingRegion;
            },
            function (OperatingRegion $entity) {
                return $entity->isDefault();
            }
        );
    }
}
