<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\UserBundle\Model\UserManager;

class UserTest extends FixturesWebTestCase
{
    public function testHydration()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals('xuser', $entityOne->getUsername());
    }

    public function testSideBarOpen()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertTrue($entityOne->isSideBarOpen());
        $entityOne->toggleSidebar();
        $this->assertFalse($entityOne->isSideBarOpen());
    }

    /**
     * @return UserManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.user');
    }
}
