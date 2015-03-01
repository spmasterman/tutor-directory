<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\Phone;
use Fitch\TutorBundle\Model\PhoneManagerInterface;

class PhoneManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        FindModelManagerTestTrait;

    const FIXTURE_COUNT = 6;

    /** @var  PhoneManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.phone');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, '+1 123 1111111 (Home)', function (Phone $entity) {
            return (string) $entity;
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, '+1 123 1111111 (Home)', function (Phone $entity) {
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
            function (Phone $entity) {
                $entity
                    ->setType('t')
                    ->setPreferred(true)
                    ->setCountry(null)
                    ->setNumber('1');
            },
            function (Phone $entity) {
                $entity
                    ->setNumber('p2');
            },
            function (Phone $entity) {
                $entity
                    ->setNumber('p3');
            },
            function (Phone $entity) {
                return (bool) 'p2' == $entity->getNumber();
            }
        );
    }
}
