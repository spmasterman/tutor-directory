<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\DefaultableModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\ReportManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;

class TutorManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait,
        DefaultableModelManagerTestTrait;

    const FIXTURE_COUNT = 3;

    /** @var  TutorManagerInterface */
    protected $modelManager;

    /** @var  ReportManagerInterface */
    protected $reportManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.tutor');
        $this->reportManager = $this->container->get('fitch.manager.report');

    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test Tutor One', function (Tutor $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Tutor One', function (Tutor $entity) {
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
            function (Tutor $entity) {
                $entity
                    ->setName('t')
                    ->setBio('b')
                    ->setLinkedInURL('l')
                ;
            },
            function (Tutor $entity) {
                $entity
                    ->setName('p2');
            },
            function (Tutor $entity) {
                $entity
                    ->setName('p3');
            },
            function (Tutor $entity) {
                return (bool) 'p2' == $entity->getName();
            }
        );
    }

    public function testPopulateTable()
    {
//        $tableData = $this->modelManager->populateTable();
//        $this->assertCount(self::FIXTURE_COUNT, $tableData);

        // TODO - this could (and should) be a lot more comprehensive. This is our
        // opportunity to test that f.ex. the contents of private files are not index-able
        // by non privileged users etc, and also that no one has inadvertently borked the
        // SQL statement in the Repo, that is quite sensitive to change.

        // Alas, the tests running under SQLite will fail, because the query has some
        // MySQL specifics. Needs looking at a bit. Another time though.
    }

    public function testReportData()
    {
        // This gets tested in a functional controller test - but just being explicit here

        $report = $this->reportManager->findById(1);
        $serializer = $this->container->get('jms_serializer');

        $data =
            $this->modelManager->getReportData(
                $serializer ->deserialize(
                    $report->getDefinition(),
                    'Fitch\TutorBundle\Model\ReportDefinition',
                    'json'
                )
            );

        $this->assertCount(1, $data);
        $this->assertContainsOnlyInstancesOf('Fitch\TutorBundle\Entity\Tutor', $data);
    }
}


//extends FixturesWebTestCase
//{
//    public function testFindAll()
//    {
//        $allEntities = $this->getModelManager()->findAll();
//        $this->assertCount(3, $allEntities, "Should return three file types");
//
//        $this->assertEquals('Test Tutor One', $allEntities[0]->getName());
//        $this->assertEquals('Test Tutor Two', $allEntities[1]->getName());
//        $this->assertEquals('Test Tutor Three', $allEntities[2]->getName());
//    }
//
//    public function testFindById()
//    {
//        $entityOne = $this->getModelManager()->findById(1);
//
//        $this->assertEquals('Test Tutor One', $entityOne->getName());
//    }
//
//    public function testLifeCycle()
//    {
//        // Check that there are 3 entries
//        $allEntities = $this->getModelManager()->findAll();
//        $this->assertCount(3, $allEntities, "Should return three entities");
//
//        // Create new one - these services probably should all be stubbed/mocked to test this in isolation ??
//        $newEntity = $this->getModelManager()->createEntity();
//
//        $newEntity
//            ->setName('t')
//            ->setBio('b')
//            ->setLinkedInURL('l')
//        ;
//        $this->getModelManager()->saveEntity($newEntity);
//
//        // Check that there are 4 entries, and the new one is Timestamped correctly
//        $allEntities = $this->getModelManager()->findAll();
//        $this->assertCount(4, $allEntities, "Should return four entities");
//        $this->assertNotNull($allEntities[3]->getCreated());
//        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());
//
//        // Updated shouldn't change until persisted
//        $newEntity->setName('t2');
//        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());
//
//        sleep(1);
//
//        $this->getModelManager()->saveEntity($newEntity);
//        $this->assertNotEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());
//
//        // Check that when we refresh it refreshes
//        $newEntity->setName('t3');
//        $this->getModelManager()->reloadEntity($newEntity);
//        $this->assertEquals('t2', $newEntity->getName());
//
//        // Check that when we remove it, it is no longer present
//        $this->getModelManager()->removeEntity($newEntity);
//        $allEntities = $this->getModelManager()->findAll();
//        $this->assertCount(3, $allEntities, "Should return three entities");
//    }
//
//    /**
//     * @return TutorManagerInterface
//     */
//    public function getModelManager()
//    {
//        return $this->container->get('fitch.manager.tutor');
//    }
//}
