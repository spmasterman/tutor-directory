<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\AddressManager;

class AddressManagerTest extends FixturesWebTestCase
{

    public function testFindAll()
    {
        $addresses = $this->getModelManager()->findAll();
        $this->assertCount(6, $addresses, "Should return six addresses");

        $this->assertEquals('1 Main Street', $addresses[0]->getStreetPrimary());
        $this->assertEquals('2 Main Street', $addresses[1]->getStreetPrimary());
        $this->assertEquals('3 Main Street', $addresses[2]->getStreetPrimary());
        $this->assertEquals('4 Main Street', $addresses[3]->getStreetPrimary());
        $this->assertEquals('5 Main Street', $addresses[4]->getStreetPrimary());
        $this->assertEquals('6 Main Street', $addresses[5]->getStreetPrimary());
    }

    public function testFindById()
    {
        $address = $this->getModelManager()->findById(1);

        $this->assertEquals('1 Main Street', $address->getStreetPrimary());
    }

    public function testLifeCycle()
    {
        // Check that there are 6 entries
        $addresses = $this->getModelManager()->findAll();
        $this->assertCount(6, $addresses, "Should return six addresses");

        // Creata new one
        $address = $this->getModelManager()->createAddress();
        $address
            ->setStreetPrimary('p')
            ->setStreetSecondary('s')
            ->setCity('c')
            ->setState('st')
            ->setZip('z')
        ;
        $this->getModelManager()->saveAddress($address);

        // Check that there are 7 entries, and the new one is Timestamped correctly
        $addresses = $this->getModelManager()->findAll();
        $this->assertCount(7, $addresses, "Should return seven addresses");
        $this->assertNotNull($addresses[6]->getCreated());
        $this->assertEquals($addresses[6]->getCreated(), $addresses[6]->getUpdated());

        // Updated shouldn't change until persisted
        $address->setStreetPrimary('p2');
        $this->assertEquals($addresses[6]->getCreated(), $addresses[6]->getUpdated());

        sleep(1);

        $this->getModelManager()->saveAddress($address);
        $this->assertNotEquals($addresses[6]->getCreated(), $addresses[6]->getUpdated());

        // Check that when we refresh it refreshes
        $address->setStreetPrimary('p3');
        $this->getModelManager()->refreshAddress($address);
        $this->assertEquals('p2', $address->getStreetPrimary());

        // Check that when we remove it, it is no longer present
        $this->getModelManager()->removeAddress($address->getId());
        $addresses = $this->getModelManager()->findAll();
        $this->assertCount(6, $addresses, "Should return six addresses");
    }

    /**
     * @return AddressManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.address');
    }
}
