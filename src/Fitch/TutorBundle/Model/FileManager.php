<?php

namespace Fitch\TutorBundle\Model;

use Doctrine\ORM\EntityManager;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\File;
use Oneup\UploaderBundle\Uploader\File\GaufretteFile;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FileManager.
 */
class FileManager extends BaseModelManager implements FileManagerInterface
{
    /** @var TutorManagerInterface $tutorManager  */
    private $tutorManager;

    /** @var FileTypeManagerInterface $fileTypeManager  */
    private $fileTypeManager;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param EntityManager            $em
     * @param string                   $class
     * @param TutorManagerInterface    $tutorManager
     * @param FileTypeManagerInterface $fileTypeManager
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        EntityManager $em,
        $class,
        TutorManagerInterface $tutorManager,
        FileTypeManagerInterface $fileTypeManager
    ) {
        parent::__construct($dispatcher, $em, $class);

        $this->tutorManager = $tutorManager;
        $this->fileTypeManager = $fileTypeManager;
    }

    /**
     * @param Request       $request
     * @param GaufretteFile $gaufretteFile
     * @param array         $metaInfo
     *
     * @return File
     */
    public function setMetaInfo(Request $request, GaufretteFile $gaufretteFile, $metaInfo)
    {
        $tutor = $this->tutorManager->findById($request->request->get('tutorPk'));
        $file = $this->createEntity();
        $tutor->addFile($file);

        // Get a reference to the UploadedFile - so we can get meta info (f.ex. the original name)
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');

        // Save both those things in our doctrine entity
        $file
            ->setName($uploadedFile->getClientOriginalName())
            ->setFileSystemKey($gaufretteFile->getKey())
            ->setFileType($this->fileTypeManager->findDefaultFileType())
            ->setMimeType($metaInfo['mimeType'])
            ->setUploader($metaInfo['uploader'])
            ->setTextContent($metaInfo['textContent']);

        // And finally persist the whole thing
        $this->tutorManager->saveEntity($tutor);

        return $file;
    }

    /**
     * Used  to identify logs generated by this class.
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.file';
    }
}
