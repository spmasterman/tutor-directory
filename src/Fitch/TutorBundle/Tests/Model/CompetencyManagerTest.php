<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Model\CompetencyManager;
use Fitch\TutorBundle\Model\CompetencyManagerInterface;

class CompetencyManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        FindModelManagerTestTrait;

    const FIXTURE_COUNT = 3;

    /** @var  CompetencyManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.competency');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(
            self::FIXTURE_COUNT,
            'Test Competency Type One (Test Level One)',
            function (Competency $entity) {
                return (string) $entity;
            }
        );
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(
            1,
            'Test Competency Type One (Test Level One)',
            function (Competency $entity) {
                return (string) $entity;
            }
        );
    }

    /**
     *
     */
    public function testLifeCycle()
    {
        $this->performLifeCycleTests(
            self::FIXTURE_COUNT,
            function (Competency $entity) {
                $entity
                    ->setNote('note')
                ;
            },
            function (Competency $entity) {
                $entity
                    ->setNote('n2');
            },
            function (Competency $entity) {
                $entity
                    ->setNote('n3');
            },
            function (Competency $entity) {
                return (bool) 'n2' == $entity->getNote();
            }
        );
    }
}
