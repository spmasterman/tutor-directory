<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\CurrencyManager;

class CurrencyManagerTest extends FixturesWebTestCase
{
    public function testFindAll()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(4, $allEntities, "Should return three entities");

        $this->assertEquals('ONE - Test Currency One', (string) $allEntities[0]);
        $this->assertEquals('TWO - Test Currency Two', (string) $allEntities[1]);
        $this->assertEquals('THR - Test Currency Three', (string) $allEntities[2]);
        $this->assertEquals('FOR - Test Currency Four', (string) $allEntities[3]);
    }

    public function testFindById()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertEquals('ONE - Test Currency One', (string) $entityOne);
    }

    public function testFindDefaultCountry()
    {
        $entity = $this->getModelManager()->getDefaultCurrency();
        $this->assertNull($entity);

        $entityOne = $this->getModelManager()->findById(1);
        $entityOne->setThreeDigitCode('GBP');
        $this->getModelManager()->saveCurrency($entityOne);

        $entity = $this->getModelManager()->getDefaultCurrency();
        $this->assertEquals($entity->getThreeDigitCode(), 'GBP');
    }

    public function testPreferredChoices()
    {
        $allEntities = $this->getModelManager()->findAll();
        $preferredEntities = $this->getModelManager()->buildPreferredChoices();
        $this->assertCount(1, $preferredEntities, "Should return one entity");

        foreach ($allEntities as $entity) {
            if (in_array($entity, $preferredEntities)) {
                $this->assertTrue($entity->isPreferred());
            } else {
                $this->assertFalse($entity->isPreferred() && $entity->isActive());
            }
        }

        $sorted = $this->getModelManager()->findAllSorted();
        $this->assertTrue($sorted[0]->isPreferred());
        $this->assertTrue($sorted[0]->isActive());

        $this->assertTrue($sorted[1]->isPreferred());
        $this->assertFalse($sorted[1]->isActive());

        $this->assertFalse($sorted[2]->isPreferred());
        $this->assertTrue($sorted[2]->isActive());

        $this->assertFalse($sorted[3]->isPreferred());
        $this->assertFalse($sorted[3]->isActive());

        $addressEntities = $this->getModelManager()->buildChoices();
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
        $newEntity = $this->getModelManager()->createCurrency();
        $newEntity
            ->setName('c')
            ->setPreferred(true)
            ->setActive(false)
            ->setThreeDigitCode('tdc')
        ;
        $this->getModelManager()->saveCurrency($newEntity);

        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(5, $allEntities, "Should return 5 entities");
        $this->assertNotNull($allEntities[4]->getCreated());
        $this->assertEquals($allEntities[4]->getCreated(), $allEntities[4]->getUpdated());

        $newEntity->setName('c2');
        $this->assertEquals($allEntities[4]->getCreated(), $allEntities[4]->getUpdated());

        sleep(1);

        $this->getModelManager()->saveCurrency($newEntity);
        $this->assertNotEquals($allEntities[4]->getCreated(), $allEntities[4]->getUpdated());

        $newEntity->setName('c3');
        $this->getModelManager()->refreshCurrency($newEntity);
        $this->assertEquals('c2', $newEntity->getName());

        $this->getModelManager()->removeCurrency($newEntity->getId());
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(4, $allEntities, "Should return 4 entities");
    }

    /**
     * @return CurrencyManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.currency');
    }
}
