<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\CropInfoManagerInterface;
use Fitch\TutorBundle\Model\EmailManagerInterface;

class CropInfoTest extends FixturesWebTestCase
{
    /**
     * Pretty pointless tests really - but its a simple entity
     */
    public function testEntity()
    {
        $entity = $this->getModelManager()->createEntity();

        $entity->setHeight(1);
        $entity->setWidth(2);
        $entity->setOriginX(3);
        $entity->setOriginY(4);

        $this->assertEquals(1, $entity->getHeight());
        $this->assertEquals(2, $entity->getWidth());
        $this->assertEquals(3, $entity->getOriginX());
        $this->assertEquals(4, $entity->getOriginY());
    }

    /**
     * @return CropInfoManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.crop_info');
    }
}
