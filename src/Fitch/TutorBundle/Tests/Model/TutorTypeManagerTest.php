<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\DefaultableModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\TutorType;
use Fitch\TutorBundle\Model\TutorTypeManagerInterface;

class TutorTypeManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait,
        DefaultableModelManagerTestTrait;

    const FIXTURE_COUNT = 3;

    /** @var  TutorTypeManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.tutor_type');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test Tutor Type One', function (TutorType $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Tutor Type One', function (TutorType $entity) {
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
            function (TutorType $entity) {
                $entity
                    ->setName('n')
                    ->setDefault(false)
                ;
            },
            function (TutorType $entity) {
                $entity
                    ->setName('p2');
            },
            function (TutorType $entity) {
                $entity
                    ->setName('p3');
            },
            function (TutorType $entity) {
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
                return $entity instanceof TutorType;
            },
            function (TutorType $entity) {
                return $entity->isDefault();
            }
        );
    }
}
