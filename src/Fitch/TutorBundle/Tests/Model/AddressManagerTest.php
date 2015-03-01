<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\Address;
use Fitch\TutorBundle\Model\AddressManagerInterface;

/**
 * Class AddressManagerTest.
 */
class AddressManagerTest  extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait;

    const FIXTURE_COUNT = 6;

    /** @var  AddressManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.address');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, '1 Main Street', function (Address $entity) {
            return $entity->getStreetPrimary();
        });
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, '1 Main Street', function (Address $entity) {
            return $entity->getStreetPrimary();
        });
    }

    /**
     *
     */
    public function testLifeCycle()
    {
        $this->performLifeCycleTests(
            self::FIXTURE_COUNT,
            function (Address $entity) {
                $entity
                    ->setStreetPrimary('p')
                    ->setStreetSecondary('s')
                    ->setCity('c')
                    ->setState('st')
                    ->setZip('z');
            },
            function (Address $entity) {
                $entity
                    ->setStreetPrimary('p2');
            },
            function (Address $entity) {
                $entity
                    ->setStreetPrimary('p3');
            },
            function (Address $entity) {
                return (bool) 'p2' == $entity->getStreetPrimary();
            }
        );
    }
}
