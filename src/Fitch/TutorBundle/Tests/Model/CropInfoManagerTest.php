<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\CropInfo;
use Fitch\TutorBundle\Model\CropInfoManagerInterface;

/**
 * Class CropInfoManagerTest.
 */
class CropInfoManagerTest  extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        FindModelManagerTestTrait;

    const FIXTURE_COUNT = 0;

    /** @var  CropInfoManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.crop_info');

        // Add an entry
        $entity = new CropInfo();
        $entity->setOriginX(1)->setOriginY(1)->setHeight(1)->setWidth(1);
        $this->modelManager->saveEntity($entity);
    }

    /**
     *
     */
    public function testLifeCycle()
    {
        $this->performLifeCycleTests(
            self::FIXTURE_COUNT + 1,
            function (CropInfo $entity) {
                $entity
                    ->setOriginX(1)
                    ->setOriginY(1)
                    ->setHeight(1)
                    ->setWidth(1)
                ;
            },
            function (CropInfo $entity) {
                $entity
                    ->setOriginX(10);
            },
            function (CropInfo $entity) {
                $entity
                    ->setOriginX(20);
            },
            function (CropInfo $entity) {
                return (bool) 10 == $entity->getOriginX();
            }
        );
    }
}
