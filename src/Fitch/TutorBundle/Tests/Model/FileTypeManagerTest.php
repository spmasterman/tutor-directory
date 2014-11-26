<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\FileTypeManager;

class FileTypeManagerTest extends FixturesWebTestCase
{

    public function testFindAll()
    {
        $fileTypes = $this->getModelManager()->findAll();
        $this->assertCount(3, $fileTypes, "Should return three file types");

        $this->assertEquals('Test File Type One', $fileTypes[0]->getName());
        $this->assertEquals('Test File Type Two', $fileTypes[1]->getName());
        $this->assertEquals('Test File Type Three', $fileTypes[2]->getName());
    }

    public function testFindById()
    {
        $fileType = $this->getModelManager()->findById(1);

        $this->assertEquals('Test File Type One', $fileType->getName());
    }

    public function testLifeCycle()
    {
        // Check that there are 3 entries
        $fileTypes = $this->getModelManager()->findAll();
        $this->assertCount(3, $fileTypes, "Should return three file types");

        // Creata new one
        $newfileType = $this->getModelManager()->createFileType();
        $newfileType
            ->setName('Test')
            ->setPrivate(false)
            ->setSuitableForProfilePicture(false)
            ->setDefault(false)
        ;
        $this->getModelManager()->saveFileType($newfileType);

        // Check that there are 4 entries, and the new one is Timestamped correctly
        $fileTypes = $this->getModelManager()->findAll();
        $this->assertCount(4, $fileTypes, "Should return four file types");
        $this->assertNotNull($fileTypes[3]->getCreated());
        $this->assertEquals($fileTypes[3]->getCreated(), $fileTypes[3]->getUpdated());

        // Updated shouldn't change until persisted
        $newfileType->setName('Test (Updated)');
        $this->assertEquals($fileTypes[3]->getCreated(), $fileTypes[3]->getUpdated());

        sleep(1);

        $this->getModelManager()->saveFileType($newfileType);
        $this->assertNotEquals($fileTypes[3]->getCreated(), $fileTypes[3]->getUpdated());

        // Check that when we refresh it refreshes
        $newfileType->setName('Test (Abandoned Edit)');
        $this->getModelManager()->refreshFileType($newfileType);
        $this->assertEquals('Test (Updated)', $newfileType->getName());

        // Check that when we remove it, it is no longer present
        $this->getModelManager()->removeFileType($newfileType->getId());
        $fileTypes = $this->getModelManager()->findAll();
        $this->assertCount(3, $fileTypes, "Should return three file types");
    }

    public function testFindDefaultFileType()
    {
        $fileType = $this->getModelManager()->findDefaultFileType();

        $this->assertTrue($fileType->isDefault());
    }

    /**
     * @return FileTypeManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.file_type');
    }
}
