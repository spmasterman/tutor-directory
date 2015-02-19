<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\CommonBundle\Exception\UnhandledMimeTypeException;
use Fitch\CommonBundle\Exception\UnknownMethodException;
use Fitch\TutorBundle\Entity\File;
use Fitch\TutorBundle\Model\CropInfoManager;
use Fitch\TutorBundle\Model\FileManager;
use Fitch\TutorBundle\Model\FileTypeManager;
use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * File controller
 *

 */
class FileController extends Controller
{
    const AVATAR_WIDTH = 150;
    const AVATAR_HEIGHT = 150;
    const AVATAR_QUALITY = 90;

    /**
     * Updates a (simple) field on a file record
     *
     * @Route(
     *      "/editor/file/update",
     *      name="file_ajax_update",
     *      options={"expose"=true},
     *      condition="
                request.request.has('pk') and request.request.get('pk') > 0
            and request.request.has('name') and request.request.get('name') > ''
            and request.request.has('value')
            "
     * )
     * @Method("POST")
     * @Template()
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAction(Request $request)
    {
        try {
            $file = $this->getFileManager()->findById($request->request->get('pk'));

            $name = $request->request->get('name');
            $name = preg_replace('/\d/', '', $name); // collections are numbered address1, address2 etc

            $value = $request->request->get('value');

            switch ($name) {
                case 'fileType':
                    $fileType = $this->getFileTypeManager()->findById($value);
                    $file->setFileType($fileType);
                    break;
                default :
                    $setter = 'set' . ucfirst($name);
                    if (is_callable([$file, $setter])) {
                        $file->$setter($value);
                    } else {
                        throw new UnknownMethodException($setter . ' is not a valid File method');
                    }
            }
            $this->getFileManager()->saveFile($file);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'success' => true,
            'renderedFileRow' => $this->renderView(
                'FitchTutorBundle:Profile:file_row_inner.html.twig',
                [
                    'file' => $file,
                    'editor' => $this->isGranted('ROLE_EDITOR'),
                    'admin' => $this->isGranted('ROLE_ADMIN'),
                ]
            ),
            'renderedAvatar' => $this->renderView(
                'FitchTutorBundle:Profile:avatar.html.twig',
                [
                    'tutor' => $file->getTutor(),
                    'editor' => $this->isGranted('ROLE_EDITOR'),
                    'admin' => $this->isGranted('ROLE_ADMIN'),
                ]
            )
        ]);
    }

    /**
     * @Route("/stream/{id}", name="get_file_stream", options={"expose"=true})
     * @Method("GET")
     * @Template()
     *
     * @param File $file
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function streamAction(File $file)
    {
        $filePath = 'gaufrette://tutor/'.$file->getFileSystemKey();
        $response = new BinaryFileResponse($filePath);
        return $response;
    }

    /**
     * @Route("/avatar/{id}", name="get_file_as_avatar", options={"expose"=true})
     * @Method("GET")
     * @Template()
     *
     * @param File $file
     *
     * @throws UnhandledMimeTypeException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function avatarAction(File $file)
    {
        $targetWidth = self::AVATAR_WIDTH;
        $targetHeight = self::AVATAR_HEIGHT;
        $jpeg_quality = self::AVATAR_QUALITY;

        $src = 'gaufrette://tutor/'.$file->getFileSystemKey();

        switch($file->getMimeType()) {
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'image/jpg':
                $sourceImage = imagecreatefromjpeg($src);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($src);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif($src);
                break;
            default:
                throw new UnhandledMimeTypeException(
                    $file->getMimeType() . ' is not a suitable image type for an avatar'
                );
        }

        $destinationImage = ImageCreateTrueColor($targetWidth, $targetHeight);

        $cropInfo = $file->getCropInfo();
        if ($cropInfo) {
            imagecopyresampled(
                $destinationImage,
                $sourceImage,
                0, 0, $cropInfo->getOriginX(), $cropInfo->getOriginY(),
                $targetWidth, $targetHeight, $cropInfo->getWidth(), $cropInfo->getHeight()
            );
        } else {
            imagecopyresampled(
                $destinationImage,
                $sourceImage,
                0, 0, 0, 0,
                $targetWidth, $targetHeight, $targetWidth, $targetHeight
            );
        }

        // Use Output buffering to catch the result of the GD call (imagejpeg)
        ob_start();
        imagejpeg($destinationImage, null, $jpeg_quality);
        $image = ob_get_contents();
        ob_end_clean();

        // Then construct a response and set the content directly
        $response = new Response();
        $response->headers->set('Content-Type', 'image/jpeg');
        $response->setContent($image);
        return $response;
    }

    /**
     * Updates the CropInfo for a file record
     *
     * @Route(
     *      "/editor/file/crop",
     *      name="file_ajax_crop",
     *      options={"expose"=true},
     *      condition="
                request.request.has('pk') and request.request.get('pk') > 0
            and request.request.has('originX')
            and request.request.has('originY')
            and request.request.has('width') and request.request.get('width') > 0
            and request.request.has('height') and request.request.get('height') > 0
       "
     * )
     * @Method("POST")
     * @Template()
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function cropAction(Request $request)
    {
        try {
            $file = $this->getFileManager()->findById($request->request->get('pk'));

            $cropInfo = $file->getCropInfo();
            if (!$cropInfo) {
                $cropInfo = $this->getCropInfoManager()->createCropInfo();
            }

            $cropInfo->setOriginX($request->request->get('originX'));
            $cropInfo->setOriginY($request->request->get('originY'));
            $cropInfo->setWidth($request->request->get('width'));
            $cropInfo->setHeight($request->request->get('height'));
            $file->setCropInfo($cropInfo);
            $this->getCropInfoManager()->saveCropInfo($cropInfo);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'success' => true,
        ]);
    }

    /**
     * @Route("/download/{id}", name="get_file_download")
     * @Method("GET")
     * @Template()
     *
     * @param File $fileEntity
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadAction(File $fileEntity)
    {
        $fileSystem = $this->getFileSystemMapService()->get('tutor');
        $file = $fileSystem->read($fileEntity->getFileSystemKey());

        if(!$file) {
            throw new NotFoundHttpException('File does not exist!');
        }

        //Create And Return Response
        $response = new Response();

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileEntity->getName()
        );

        $response->headers->set('Content-Length', $fileSystem->size($fileEntity->getFileSystemKey()));
        $response->headers->set('Accept-Ranges', 'bytes');
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', $disposition);
        $response->setContent($file);

        return $response;
    }

    /**
     * @Route(
     *      "/editor/file/remove",
     *      name="file_ajax_remove",
     *      options={"expose"=true},
     *      condition="
                request.request.has('pk') and request.request.get('pk') > 0
            "
     * )
     * @Method("POST")
     * @Template()
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeAction(Request $request)
    {
        try {
            $fileEntity = $this->getFileManager()->findById($request->request->get('pk'));

            $fileSystem = $this->getFileSystemMapService()->get('tutor');
            $file = $fileSystem->get($fileEntity->getFileSystemKey());

            if(!$file) {
                throw new NotFoundHttpException('File does not exist!');
            }

            $file->delete();
            $this->getFileManager()->removeFile($fileEntity->getId());
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'success' => true,
        ]);
    }

    /**
     * @return FileManager
     */
    private function getFileManager()
    {
        return $this->get('fitch.manager.file');
    }

    /**
     * @return CropInfoManager
     */
    private function getCropInfoManager()
    {
        return $this->get('fitch.manager.crop_info');
    }


    /**
     * @return FileTypeManager
     */
    private function getFileTypeManager()
    {
        return $this->get('fitch.manager.file_type');
    }

    /**
     * @return FileSystemMap
     */
    private function getFileSystemMapService()
    {
        return $this->get('knp_gaufrette.filesystem_map');
    }
}
