<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\FileTypeManager;

class FileTypeTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $fileType = $this->getModelManager()->findById(1);
        $this->assertEquals('Test File Type One (Private)', (string)$fileType);
    }

    /**
     * @return FileTypeManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.file_type');
    }
}
