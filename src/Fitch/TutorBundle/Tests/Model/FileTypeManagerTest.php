<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\FileTypeManager;

class FileTypeManagerTest extends FixturesWebTestCase
{

    public function testFindAll()
    {
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three file types");

        $this->assertEquals('Test File Type One', $allEntities[0]->getName());
        $this->assertEquals('Test File Type Two', $allEntities[1]->getName());
        $this->assertEquals('Test File Type Three', $allEntities[2]->getName());
    }

    public function testFindById()
    {
        $entityOne = $this->getModelManager()->findById(1);

        $this->assertEquals('Test File Type One', $entityOne->getName());
    }

    public function testLifeCycle()
    {
        // Check that there are 3 entries
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three file types");

        // Creata new one
        $newEntity = $this->getModelManager()->createFileType();
        $newEntity
            ->setName('Test')
            ->setPrivate(false)
            ->setSuitableForProfilePicture(false)
            ->setDefault(false)
        ;
        $this->getModelManager()->saveFileType($newEntity);

        // Check that there are 4 entries, and the new one is Timestamped correctly
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(4, $allEntities, "Should return four file types");
        $this->assertNotNull($allEntities[3]->getCreated());
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        // Updated shouldn't change until persisted
        $newEntity->setName('Test (Updated)');
        $this->assertEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        sleep(1);

        $this->getModelManager()->saveFileType($newEntity);
        $this->assertNotEquals($allEntities[3]->getCreated(), $allEntities[3]->getUpdated());

        // Check that when we refresh it refreshes
        $newEntity->setName('Test (Abandoned Edit)');
        $this->getModelManager()->refreshFileType($newEntity);
        $this->assertEquals('Test (Updated)', $newEntity->getName());

        // Check that when we remove it, it is no longer present
        $this->getModelManager()->removeFileType($newEntity->getId());
        $allEntities = $this->getModelManager()->findAll();
        $this->assertCount(3, $allEntities, "Should return three file types");
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
