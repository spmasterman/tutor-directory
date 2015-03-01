<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\Category;
use Fitch\TutorBundle\Model\CategoryManagerInterface;

/**
 * Class CategoryManagerTest.
 */
class CategoryManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait;

    const FIXTURE_COUNT = 4;

    /** @var  CategoryManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.category');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test Category One', function (Category $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Category One', function (Category $entity) {
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
            function (Category $entity) {
                $entity->setName('b');
            },
            function (Category $entity) {
                $entity->setName('p2');
            },
            function (Category $entity) {
                $entity->setName('p3');
            },
            function (Category $entity) {
                return (bool) 'p2' == $entity->getName();
            }
        );
    }

    public function testBuildChoices()
    {
        $choices = $this->modelManager->buildChoices();

        $this->assertCount(self::FIXTURE_COUNT, $choices);

        foreach ($choices as $choice) {
            $this->assertTrue($choice instanceof Category);
        }
    }

    public function testBuildGroupedChoices()
    {
        $choices = $this->modelManager->buildGroupedChoices();
        $this->assertCount(self::FIXTURE_COUNT, $choices); // this is not *generally* true only if the manager uses Flatlist
    }

    public function testFindDefault()
    {
        $entity = $this->modelManager->findDefaultCategory();
        $this->assertTrue($entity instanceof Category);
        $this->assertTrue($entity->isDefault());
    }
}

//todo move baseclass to trait
//todo make trait for other entity behaviors (defaultable, selectable, preferred-and-active etc)
