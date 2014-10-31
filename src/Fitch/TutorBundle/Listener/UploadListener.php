<?php

namespace Fitch\TutorBundle\Listener;

use Fitch\TutorBundle\Model\FileManager;
use Fitch\TutorBundle\Model\FileTypeManager;
use Fitch\TutorBundle\Model\TutorManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Oneup\UploaderBundle\Uploader\File\GaufretteFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadListener
{
    /** @var FileManager */
    protected $fileManager;

    /** @var FileTypeManager */
    protected $fileTypeManager;

    /** @var TutorManager */
    protected $tutorManager;

    public function __construct(TutorManager $tutorManager, FileManager $fileManager, FileTypeManager $fileTypeManager)
    {
        $this->fileManager = $fileManager;
        $this->fileTypeManager = $fileTypeManager;
        $this->tutorManager = $tutorManager;
    }

    /**
     * @param PostPersistEvent $event
     */
    public function onUpload(PostPersistEvent $event)
    {
        $request = $event->getRequest();

        $tutor = $this->tutorManager->findById($request->request->get('tutorPk'));
        $file = $this->fileManager->createFile();
        $tutor->addFile($file);


        // Get a reference to the UploadedFile - so we can get meta info (f.ex. the original name)
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');

        // Get a reference to the Gaufrette filesystem entry (so we can persist the key)
        /** @var GaufretteFile $gaufretteFile */
        $gaufretteFile = $event->getFile();

        // Save both those things in our doctrine entity
        $file
            ->setName($uploadedFile->getClientOriginalName())
            ->setFileSystemKey($gaufretteFile->getKey())
            ->setFileType($this->fileTypeManager->findDefaultFileType())
        ;

        // And finally persist the whole thing
        $this->tutorManager->saveTutor($tutor);
    }
}