<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\CountryManager;

class CountryManagerTest extends FixturesWebTestCase
{
    public function testFindAll()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(4, $allEntities, "Should return three entities");

        $this->assertEquals('Test Country One (ONE) +1', (string) $allEntities[0]);
        $this->assertEquals('Test Country Two (TWO) +2', (string) $allEntities[1]);
        $this->assertEquals('Test Country Three (THR) +3', (string) $allEntities[2]);
        $this->assertEquals('Test Country Four (FOR) +4', (string) $allEntities[3]);
    }

    public function testFindById()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertEquals('Test Country One (ONE) +1', (string) $entityOne);
        $this->assertEquals('Test Region One', $entityOne->getDefaultRegion()->getName());
    }

    public function testFindDefaultCountry()
    {
        $entity = $this->getModelManager()->getDefaultCountry();
        $this->assertEquals($entity->getTwoDigitCode(), 'GB');

        $entityOne = $this->getModelManager()->findById(4);
        $entityOne->setTwoDigitCode('XX');
        $this->getModelManager()->saveCountry($entityOne);

        $entity = $this->getModelManager()->getDefaultCountry();
        $this->assertNull($entity);
    }

    public function testPreferredChoices()
    {
        $allEntities = $this->getModelManager()->findAll();
        $preferredEntities = $this->getModelManager()->buildPreferredChoicesForAddress();
        $this->assertCount(2, $preferredEntities, "Should return two entities");

        foreach ($allEntities as $entity) {
            if (in_array($entity, $preferredEntities)) {
                $this->assertTrue($entity->isPreferred());
            } else {
                $this->assertFalse($entity->isPreferred());
            }
        }

        $sorted = $this->getModelManager()->findAllSorted();
        $this->assertTrue($sorted[0]->isPreferred());
        $this->assertTrue($sorted[0]->isActive());

        $this->assertTrue($sorted[1]->isPreferred());
        $this->assertTrue($sorted[1]->isActive());

        $this->assertFalse($sorted[2]->isPreferred());
        $this->assertTrue($sorted[2]->isActive());

        $this->assertFalse($sorted[3]->isPreferred());
        $this->assertFalse($sorted[3]->isActive());

        $addressEntities = $this->getModelManager()->buildChoicesForAddress();
        foreach ($allEntities as $entity) {
            if (in_array($entity, $addressEntities)) {
                $this->assertTrue($entity->isActive());
            } else {
                $this->assertFalse($entity->isActive());
            }
        }
    }

    public function testLifeCycle()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(4, $allEntities, "Should return 4 entities");

        // Create new one
        $newEntity = $this->getModelManager()->createCountry();
        $newEntity
            ->setName('c')
            ->setDefaultRegion(null)
            ->setDialingCode('dc')
            ->setPreferred(true)
            ->setThreeDigitCode('123')
            ->setTwoDigitCode('12')
            ->setActive(false)
        ;
        $this->getModelManager()->saveCountry($newEntity);

        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(5, $allEntities, "Should return 5 entities");
        $this->assertNotNull($allEntities[4]->getCreated());
        $this->assertEquals($allEntities[4]->getCreated(), $allEntities[4]->getUpdated());

        $newEntity->setName('c2');
        $this->assertEquals($allEntities[4]->getCreated(), $allEntities[4]->getUpdated());

        sleep(1);

        $this->getModelManager()->saveCountry($newEntity);
        $this->assertNotEquals($allEntities[4]->getCreated(), $allEntities[4]->getUpdated());

        $newEntity->setName('c3');
        $this->getModelManager()->refreshCountry($newEntity);
        $this->assertEquals('c2', $newEntity->getName());

        $this->getModelManager()->removeCountry($newEntity->getId());
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(4, $allEntities, "Should return 4 entities");
    }

    /**
     * @return CountryManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.country');
    }
}
