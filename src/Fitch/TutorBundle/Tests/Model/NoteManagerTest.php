<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\Note;
use Fitch\TutorBundle\Model\NoteManagerInterface;

class NoteManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait;

    const FIXTURE_COUNT = 3;

    /** @var  NoteManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.note');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test Note One', function (Note $entity) {
            return $entity->getBody();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Note One', function (Note $entity) {
            return $entity->getBody();
        });
    }

    /**
     *
     */
    public function testLifeCycle()
    {
        $this->performLifeCycleTests(
            self::FIXTURE_COUNT,
            function (Note $entity) {
                $entity
                    ->setBody('n')
                    ->setKey('t')
                ;
            },
            function (Note $entity) {
                $entity
                    ->setBody('p2');
            },
            function (Note $entity) {
                $entity
                    ->setBody('p3');
            },
            function (Note $entity) {
                return (bool) 'p2' == $entity->getBody();
            }
        );
    }
}
