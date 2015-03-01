<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestCase;
use Fitch\TutorBundle\Entity\BusinessArea;

/**
 * Class BusinessAreaManagerTest.
 */
class BusinessAreaManagerTest extends TimestampableModelManagerTestCase
{
    const FIXTURE_COUNT = 4;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.business_area');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'Test Business Area 1', function (BusinessArea $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Business Area 1', function (BusinessArea $entity) {
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
            function (BusinessArea $entity) {
                $entity
                    ->setName('b')
                    ->setCode('c')
                    ->setPrependToCategoryName(false)
                    ->setDisplayAsCode(false)
                    ->setDefault(false);
            },
            function (BusinessArea $entity) {
                $entity
                    ->setName('p2');
            },
            function (BusinessArea $entity) {
                $entity
                    ->setName('p3');
            },
            function (BusinessArea $entity) {
                return (bool) 'p2' == $entity->getName();
            }
        );
    }

    public function testBuildChoices()
    {
        $choices = $this->modelManager->buildChoices();

        $this->assertCount(self::FIXTURE_COUNT, $choices);

        foreach ($choices as $choice) {
            $this->assertTrue($choice instanceof BusinessArea);
        }
    }

    public function testBuildGroupedChoices()
    {
        $choices = $this->modelManager->buildGroupedChoices();
        $this->assertCount(self::FIXTURE_COUNT, $choices); // this is not *generally* true only if the manager uses Flatlist
    }

    public function testFindDefault()
    {
        $entity = $this->modelManager->findDefaultBusinessArea();
        $this->assertTrue($entity instanceof BusinessArea);
        $this->assertTrue($entity->isDefault());
    }
}

//todo move baseclass to trait
//todo make trait for other entity behaviors (defaultable, selectable, preferred-and-active etc)