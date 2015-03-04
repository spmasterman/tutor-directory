<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Entity\DefaultableEntityInterface;
use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\DefaultableModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\FileType;
use Fitch\TutorBundle\Model\FileTypeManagerInterface;

class FileTypeManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        DefaultableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait;

    const FIXTURE_COUNT = 3;

    /** @var  FileTypeManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.file_type');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test File Type One', function (FileType $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test File Type One', function (FileType $entity) {
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
            function (FileType $entity) {
                $entity
                    ->setName('Test')
                    ->setPrivate(false)
                    ->setSuitableForProfilePicture(false)
                    ->setDefault(false)
                    ->setDisplayWithBio(false)
                ;
            },
            function (FileType $entity) {
                $entity->setName('p2');
            },
            function (FileType $entity) {
                $entity->setName('p3');
            },
            function (FileType $entity) {
                return (bool) 'p2' == $entity->getName();
            }
        );
    }

    public function testBuildGroupedChoices()
    {
        $choices = $this->modelManager->buildGroupedChoices();
        $this->assertCount(self::FIXTURE_COUNT, $choices);
        // this is not *generally* true only if the manager uses Flatlist
    }

    public function testFindDefault()
    {
        $this->performFindDefaultTest(
            function ($entity) {
                return $entity instanceof FileType;
            },
            function (DefaultableEntityInterface $entity) {
                return $entity->isDefault();
            }
        );
    }
}
