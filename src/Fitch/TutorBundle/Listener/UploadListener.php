<?php

namespace Fitch\TutorBundle\Listener;

use Fitch\TutorBundle\Model\FileManager;
use Fitch\TutorBundle\Model\FileTypeManager;
use Fitch\TutorBundle\Model\TutorManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Oneup\UploaderBundle\Event\PreUploadEvent;
use Oneup\UploaderBundle\Uploader\File\GaufretteFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Templating\EngineInterface;

class UploadListener
{
    /** @var FileManager */
    protected $fileManager;

    /** @var FileTypeManager */
    protected $fileTypeManager;

    /** @var TutorManager */
    protected $tutorManager;

    /** @var  EngineInterface */
    protected $templatingService;

    /** @var  string */
    protected $mimeType;

    public function __construct(
        TutorManager $tutorManager,
        FileManager $fileManager,
        FileTypeManager $fileTypeManager,
        EngineInterface $templatingService
    ) {
        $this->fileManager = $fileManager;
        $this->fileTypeManager = $fileTypeManager;
        $this->tutorManager = $tutorManager;
        $this->templatingService = $templatingService;
    }

    /**
     * @param PreUploadEvent $event
     */
    public function onPreUpload(PreUploadEvent $event)
    {
        // Save the mimeType before the file gets put into storage (and potentially needs streaming back to find the it)
        $request = $event->getRequest();
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        $this->mimeType = $uploadedFile->getMimeType();
    }

    /**
     * @param PostPersistEvent $event
     */
    public function onPostPersist(PostPersistEvent $event)
    {
        $response = $event->getResponse();

        try {
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
                ->setMimeType($this->mimeType);
            ;
            $this->mimeType = null;

            // And finally persist the whole thing
            $this->tutorManager->saveTutor($tutor);

            // now pass back the rendered row to add to the file list
            $response['fileRow'] = $this->templatingService->render(
                'FitchTutorBundle:Profile:file_row.html.twig',
                [
                    'file' => $file,
                ]
            );
        } catch (\Exception $e) {
            $response['success'] = false;
        }
    }
}
