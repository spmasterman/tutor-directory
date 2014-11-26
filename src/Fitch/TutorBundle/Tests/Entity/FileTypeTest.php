<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\FileTypeManager;

class FileTypeTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals('Test File Type One (Private)', (string)$entityOne);
    }

    /**
     * @return FileTypeManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.file_type');
    }
}
