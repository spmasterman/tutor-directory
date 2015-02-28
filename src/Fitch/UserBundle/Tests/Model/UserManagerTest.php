<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\UserBundle\Model\UserManager;
use Fitch\UserBundle\Model\UserManagerInterface;

class UserManagerTest extends FixturesWebTestCase
{
    public function testFindAll()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(5, $allEntities, "Should return six entities");

        $this->assertEquals('xuser', $allEntities[0]->getUsername());
        $this->assertEquals('xdisabled', $allEntities[1]->getUsername());
        $this->assertEquals('xeditor', $allEntities[2]->getUsername());
        $this->assertEquals('xadmin', $allEntities[3]->getUsername());
        $this->assertEquals('xsuper', $allEntities[4]->getUsername());
    }

    public function testFindById()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertEquals('xuser', $entityOne->getUsername());
    }

    public function testLifeCycle()
    {
        // Check that there are 5 entries
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(5, $allEntities, "Should return five entities");

        // Create new one
        $newEntity = $this->getModelManager()->createUser();
        $newEntity
            ->setFullName('u')
            ->setSideBarOpen(true)
            ->setEmail('a@b.c')
            ->setPlainPassword('pw')
            ->setEnabled(true)
            ->setUsername('u')
            ->setRoles(['ROLE_USER'])
        ;
        $this->getModelManager()->saveEntity($newEntity);

        // Check that there are 6 entries, and the new one is Timestamped correctly
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(6, $allEntities, "Should return seven entities");
        $this->assertNotNull($allEntities[5]->getCreated());
        $this->assertEquals($allEntities[5]->getCreated(), $allEntities[5]->getUpdated());

        // Updated shouldn't change until persisted
        $newEntity->setFullName('u2');
        $this->assertEquals($allEntities[5]->getCreated(), $allEntities[5]->getUpdated());

        sleep(1);

        $this->getModelManager()->saveEntity($newEntity);
        $this->assertNotEquals($allEntities[5]->getCreated(), $allEntities[5]->getUpdated());

        // Check that when we refresh it refreshes
        $newEntity->setFullName('u3');
        $this->getModelManager()->reloadUser($newEntity);
        $this->assertEquals('u2', $newEntity->getFullName());

        // Check that when we remove it, it is no longer present
        $this->getModelManager()->removeUser($newEntity->getId());
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(5, $allEntities, "Should return five entities");
    }

    /**
     * @return UserManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.user');
    }
}
