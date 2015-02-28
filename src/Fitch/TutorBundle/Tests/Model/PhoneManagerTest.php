<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\PhoneManager;

class PhoneManagerTest extends FixturesWebTestCase
{
    public function testFindAll()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(6, $allEntities, "Should return six entities");

        $this->assertEquals('+1 123 1111111 (Home)', (string) $allEntities[0]);
        $this->assertEquals('+1 123 222-222 (Office)', (string) $allEntities[1]);
        $this->assertEquals('+2 123 3 (Home)', (string) $allEntities[2]);
        $this->assertEquals('+2 123 444-4444 (Office)', (string) $allEntities[3]);
        $this->assertEquals('+3 123 555555 (Home)', (string) $allEntities[4]);
        $this->assertEquals('+3 123 666666 (Office)', (string) $allEntities[5]);
    }

    public function testFindById()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertEquals('+1 123 1111111 (Home)', (string) $entityOne);
    }

    public function testLifeCycle()
    {
        // Check that there are 6 entries
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(6, $allEntities, "Should return six entities");

        // Create new one
        $newEntity = $this->getModelManager()->createEntity();
        $newEntity
            ->setType('t')
            ->setPreferred(true)
            ->setCountry(null)
            ->setNumber('1')
        ;
        $this->getModelManager()->savePhone($newEntity);

        // Check that there are 7 entries, and the new one is Timestamped correctly
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(7, $allEntities, "Should return seven entities");
        $this->assertNotNull($allEntities[6]->getCreated());
        $this->assertEquals($allEntities[6]->getCreated(), $allEntities[6]->getUpdated());

        // Updated shouldn't change until persisted
        $newEntity->setNumber('2');
        $this->assertEquals($allEntities[6]->getCreated(), $allEntities[6]->getUpdated());

        sleep(1);

        $this->getModelManager()->savePhone($newEntity);
        $this->assertNotEquals($allEntities[6]->getCreated(), $allEntities[6]->getUpdated());

        // Check that when we refresh it refreshes
        $newEntity->setNumber('3');
        $this->getModelManager()->reloadEntity($newEntity);
        $this->assertEquals('2', $newEntity->getNumber());

        // Check that when we remove it, it is no longer present
        $this->getModelManager()->removeEntity($newEntity->getId());
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(6, $allEntities, "Should return six entities");
    }

    /**
     * @return PhoneManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.phone');
    }
}
