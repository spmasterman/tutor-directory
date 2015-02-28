<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\FileTypeManagerInterface;

class FileTypeTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals('Test File Type One (Private)', (string) $entityOne);
    }

    public function testSuitableForProfilePicture()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertFalse($entityOne->isSuitableForProfilePicture());
        $entityOne->setSuitableForProfilePicture(true);
        $this->assertTrue($entityOne->isSuitableForProfilePicture());
    }

    /**
     * @return FileTypeManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.file_type');
    }
}
