<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\EmailManager;

class EmailManagerTest extends FixturesWebTestCase
{

    public function testFindAll()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(6, $allEntities, "Should return six addresses");

        $this->assertEquals('test_email_1@example.com', (string)$allEntities[0]);
        $this->assertEquals('test_email_2@example.com', (string)$allEntities[1]);
        $this->assertEquals('test_email_3@example.com', (string)$allEntities[2]);
        $this->assertEquals('test_email_4@example.com', (string)$allEntities[3]);
        $this->assertEquals('test_email_5@example.com', (string)$allEntities[4]);
        $this->assertEquals('test_email_6@example.com', (string)$allEntities[5]);
    }

    public function testFindById()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertEquals('test_email_1@example.com', (string)$entityOne);
        $this->assertEquals('primary', $entityOne->getType());
    }

    public function testLifeCycle()
    {
        // Check that there are 6 entries
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(6, $allEntities, "Should return six entities");

        // Create new one
        $newEntity = $this->getModelManager()->createEmail();
        $newEntity
            ->setType('t')
            ->setAddress('a@b.c')
        ;
        $this->getModelManager()->saveEmail($newEntity);

        // Check that there are 7 entries, and the new one is Timestamped correctly
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(7, $allEntities, "Should return seven entities");
        $this->assertNotNull($allEntities[6]->getCreated());
        $this->assertEquals($allEntities[6]->getCreated(), $allEntities[6]->getUpdated());

        // Updated shouldn't change until persisted
        $newEntity->setAddress('a2@b.c');
        $this->assertEquals($allEntities[6]->getCreated(), $allEntities[6]->getUpdated());

        sleep(1);

        $this->getModelManager()->saveEmail($newEntity);
        $this->assertNotEquals($allEntities[6]->getCreated(), $allEntities[6]->getUpdated());

        // Check that when we refresh it refreshes
        $newEntity->setAddress('a3@b.c');
        $this->getModelManager()->refreshEmail($newEntity);
        $this->assertEquals('a2@b.c', $newEntity->getAddress());

        // Check that when we remove it, it is no longer present
        $this->getModelManager()->removeEmail($newEntity->getId());
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(6, $allEntities, "Should return six entities");
    }

    /**
     * @return EmailManager
     *
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.email');
    }
}
