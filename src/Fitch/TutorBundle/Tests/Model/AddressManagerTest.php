<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\AddressManager;
use Fitch\TutorBundle\Model\Interfaces\AddressManagerInterface;

class AddressManagerTest extends FixturesWebTestCase
{
    public function testFindAll()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(6, $allEntities, "Should return six entities");

        $this->assertEquals('1 Main Street', $allEntities[0]->getStreetPrimary());
        $this->assertEquals('2 Main Street', $allEntities[1]->getStreetPrimary());
        $this->assertEquals('3 Main Street', $allEntities[2]->getStreetPrimary());
        $this->assertEquals('4 Main Street', $allEntities[3]->getStreetPrimary());
        $this->assertEquals('5 Main Street', $allEntities[4]->getStreetPrimary());
        $this->assertEquals('6 Main Street', $allEntities[5]->getStreetPrimary());
    }

    public function testFindById()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertEquals('1 Main Street', $entityOne->getStreetPrimary());
    }

    public function testLifeCycle()
    {
        // Check that there are 6 entries
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(6, $allEntities, "Should return six entities");

        // Create new one
        $newEntity = $this->getModelManager()->createAddress();
        $newEntity
            ->setStreetPrimary('p')
            ->setStreetSecondary('s')
            ->setCity('c')
            ->setState('st')
            ->setZip('z')
        ;
        $this->getModelManager()->saveAddress($newEntity);

        // Check that there are 7 entries, and the new one is Timestamped correctly
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(7, $allEntities, "Should return seven entities");
        $this->assertNotNull($allEntities[6]->getCreated());
        $this->assertEquals($allEntities[6]->getCreated(), $allEntities[6]->getUpdated());

        // Updated shouldn't change until persisted
        $newEntity->setStreetPrimary('p2');
        $this->assertEquals($allEntities[6]->getCreated(), $allEntities[6]->getUpdated());

        sleep(1);

        $this->getModelManager()->saveAddress($newEntity);
        $this->assertNotEquals($allEntities[6]->getCreated(), $allEntities[6]->getUpdated());

        // Check that when we refresh it refreshes
        $newEntity->setStreetPrimary('p3');
        $this->getModelManager()->refreshAddress($newEntity);
        $this->assertEquals('p2', $newEntity->getStreetPrimary());

        // Check that when we remove it, it is no longer present
        $this->getModelManager()->removeAddress($newEntity->getId());
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(6, $allEntities, "Should return six entities");
    }

    /**
     * @return AddressManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.address');
    }
}
