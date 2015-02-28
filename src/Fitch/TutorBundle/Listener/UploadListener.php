<?php

namespace Fitch\TutorBundle\Listener;

use Fitch\CommonBundle\Model\UserCallableInterface;
use Fitch\TutorBundle\Model\FileManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Oneup\UploaderBundle\Event\PreUploadEvent;
use Smalot\PdfParser\Parser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class UploadListener.
 */
class UploadListener
{
    /** @var FileManager */
    protected $fileManager;

    /** @var  EngineInterface */
    protected $templatingService;

    /** @var  string */
    protected $mimeType;

    /** @var  string */
    protected $textContent;

    /** @var  UserCallableInterface */
    protected $userCallable;

    /** @var  AuthorizationCheckerInterface */
    protected $authorizationChecker;

    /**
     * @param FileManager                   $fileManager
     * @param EngineInterface               $templatingService
     * @param UserCallableInterface         $userCallable
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        FileManager $fileManager,
        EngineInterface $templatingService,
        UserCallableInterface $userCallable,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->fileManager = $fileManager;
        $this->templatingService = $templatingService;
        $this->userCallable = $userCallable;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Happens once the file is on the server, but before its been put into its final storage. Use this to
     * grab info about the file etc - reading it back from storage could be a slow operation (i.e. FTP).
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
            $metaInfo = [
                'textContent' => $this->textContent,
                'mimeType' => $this->mimeType,
                'uploader' => $this->userCallable->getCurrentUser(),
            ];

            $file = $this->fileManager->setMetaInfo($event->getRequest(), $event->getFile(), $metaInfo);

            // now pass back the rendered row to add to the file list
            $response['fileRow'] = $this->templatingService->render(
                'FitchTutorBundle:Profile:file_row.html.twig',
                [
                    'file' => $file,
                    'isEditor' => (bool) $this->authorizationChecker->isGranted('ROLE_CAN_EDIT_TUTOR'),
                    'isAdmin' => (bool) $this->authorizationChecker->isGranted('ROLE_CAN_ACCESS_SENSITIVE_DATA'),
                ]
            );
            $response['success'] = true;
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }
    }
}
