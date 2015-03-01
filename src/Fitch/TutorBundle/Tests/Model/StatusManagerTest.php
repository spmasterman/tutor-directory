<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\DefaultableModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\Status;
use Fitch\TutorBundle\Model\StatusManagerInterface;

class StatusManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait,
        DefaultableModelManagerTestTrait;

    const FIXTURE_COUNT = 3;

    /** @var  StatusManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.status');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test Status One', function (Status $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Status One', function (Status $entity) {
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
            function (Status $entity) {
                $entity
                    ->setName('n')
                    ->setDefault(false)
                ;
            },
            function (Status $entity) {
                $entity
                    ->setName('p2');
            },
            function (Status $entity) {
                $entity
                    ->setName('p3');
            },
            function (Status $entity) {
                return (bool) 'p2' == $entity->getName();
            }
        );
    }

    /**
     *
     */
    public function testFindDefault()
    {
        $this->performFindDefaultTest(
            function ($entity) {
                return $entity instanceof Status;
            },
            function (Status $entity) {
                return $entity->isDefault();
            }
        );
    }
}
