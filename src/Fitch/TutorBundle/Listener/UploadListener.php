<?php

namespace Fitch\TutorBundle\Listener;

use Fitch\TutorBundle\Model\FileManager;
use Fitch\TutorBundle\Model\FileTypeManager;
use Fitch\TutorBundle\Model\TutorManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Oneup\UploaderBundle\Event\PreUploadEvent;
use Oneup\UploaderBundle\Uploader\File\GaufretteFile;
use Smalot\PdfParser\Parser;
use Symfony\Component\DependencyInjection\ContainerInterface;
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

    /** @var  string */
    protected $textContent;

    /** @var  ContainerInterface */
    protected  $container;

    public function __construct(
        TutorManager $tutorManager,
        FileManager $fileManager,
        FileTypeManager $fileTypeManager,
        EngineInterface $templatingService,
        ContainerInterface $container
    ) {
        $this->fileManager = $fileManager;
        $this->fileTypeManager = $fileTypeManager;
        $this->tutorManager = $tutorManager;
        $this->templatingService = $templatingService;
        $this->container = $container;
    }

    /**
     * Happens once the file os on the server, but before its been put into its final storage. Use this to
     * grab info about the file etc - reading it back from storage could be a slow operation (i.e. FTP)
     *
     * @param PreUploadEvent $event
     */
    public function onPreUpload(PreUploadEvent $event)
    {
        $request = $event->getRequest();
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        $this->mimeType = $uploadedFile->getMimeType();

        if ($this->mimeType == 'application/pdf') {
            try {
                $parser = new Parser();
                $this->textContent = $parser->parseFile($uploadedFile->getRealPath())->getText();
            } catch (\Exception $e) {
                $this->textContent = $e->getMessage();
            }
        } else {
            $this->textContent = '';
        }
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

            $security = $this->container->get('security.context');
            $currentUser = $security->getToken()->getUser();

            // Save both those things in our doctrine entity
            $file
                ->setName($uploadedFile->getClientOriginalName())
                ->setFileSystemKey($gaufretteFile->getKey())
                ->setFileType($this->fileTypeManager->findDefaultFileType())
                ->setMimeType($this->mimeType)
                ->setUploader($currentUser)
                ->setTextContent($this->textContent)
            ;
            $this->mimeType = null;

            // And finally persist the whole thing
            $this->tutorManager->saveTutor($tutor);

            // now pass back the rendered row to add to the file list
            $response['fileRow'] = $this->templatingService->render(
                'FitchTutorBundle:Profile:file_row.html.twig',
                [
                    'file' => $file,
                    'isEditor' => (bool) $security->isGranted('ROLE_CAN_EDIT_TUTOR'),
                    'isAdmin' => (bool) $security->isGranted('ROLE_CAN_ACCESS_SENSITIVE_DATA'),
                ]
            );
            $response['success'] = true;
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }
    }
}
