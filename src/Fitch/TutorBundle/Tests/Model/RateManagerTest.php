<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\Rate;
use Fitch\TutorBundle\Model\RateManagerInterface;

class RateManagerTest  extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait;

    const FIXTURE_COUNT = 3;
    const CHOICES_COUNT = 3;

    /** @var  RateManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.rate');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test Rate One', function (Rate $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Rate One', function (Rate $entity) {
            return $entity->getName();
        });
    }

    public function testBuildChoices()
    {
        $this->performBuildChoicesTest(
            self::CHOICES_COUNT,
            function ($entity) {
                return is_string($entity) ;
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
            function (Rate $entity) {
                $entity
                    ->setName('n')
                    ->setAmount(1)
                ;
            },
            function (Rate $entity) {
                $entity
                    ->setName('n2');
            },
            function (Rate $entity) {
                $entity
                    ->setName('n3');
            },
            function (Rate $entity) {
                // Check Logs:
                $logs = $this->modelManager->getLogs($entity);
                $this->assertCount(2, $logs);
                $this->assertInstanceOf('Gedmo\Loggable\Entity\LogEntry', $logs[0]);
                $this->assertInstanceOf('Gedmo\Loggable\Entity\LogEntry', $logs[1]);

                $this->assertEquals(2, $logs[0]->getVersion());
                $this->assertEquals('update', $logs[0]->getAction());
                $this->assertEquals(['name' => 'n2'], $logs[0]->getData());

                $this->assertEquals(1, $logs[1]->getVersion());
                $this->assertEquals('create', $logs[1]->getAction());
                $this->assertEquals(['name' => 'n', 'amount' => 1], $logs[1]->getData());

                return (bool) 'p2' == $entity->getName();
            }
        );
    }
}
