<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\DefaultableModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\Proficiency;
use Fitch\TutorBundle\Model\ProficiencyManagerInterface;

class ProficiencyManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait,
        DefaultableModelManagerTestTrait;

    const FIXTURE_COUNT = 3;

    /** @var  ProficiencyManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.proficiency');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test Proficiency One', function (Proficiency $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Proficiency One', function (Proficiency $entity) {
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
            function (Proficiency $entity) {
                $entity
                    ->setName('n')
                    ->setColor('#334455')
                    ->setDefault(false)
                    ;
            },
            function (Proficiency $entity) {
                $entity
                    ->setName('p2');
            },
            function (Proficiency $entity) {
                $entity
                    ->setName('p3');
            },
            function (Proficiency $entity) {
                return (bool) 'p2' == $entity->getName();
            }
        );
    }

    public function testFindOrCreate()
    {
        $this->performFindOrCreateTest(function (Proficiency $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testFindDefault()
    {
        $this->performFindDefaultTest(
            function ($entity) {
                return $entity instanceof Proficiency;
            },
            function (Proficiency $entity) {
                return $entity->isDefault();
            }
        );
    }
}
