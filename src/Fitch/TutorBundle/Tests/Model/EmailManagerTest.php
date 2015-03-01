<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\Email;
use Fitch\TutorBundle\Model\EmailManagerInterface;

class EmailManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        FindModelManagerTestTrait;

    const FIXTURE_COUNT = 6;

    /** @var  EmailManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.email');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'test_email_1@example.com', function (Email $entity) {
            return $entity->getAddress();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'test_email_1@example.com', function (Email $entity) {
            return $entity->getAddress();
        });
    }

    /**
     *
     */
    public function testLifeCycle()
    {
        $this->performLifeCycleTests(
            self::FIXTURE_COUNT,
            function (Email $entity) {
                $entity
                    ->setAddress('p')
                    ->setType('s')
                ;
            },
            function (Email $entity) {
                $entity
                    ->setAddress('p2');
            },
            function (Email $entity) {
                $entity
                    ->setAddress('p3');
            },
            function (Email $entity) {
                return (bool) 'p2' == $entity->getAddress();
            }
        );
    }
}
