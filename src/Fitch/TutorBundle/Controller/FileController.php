<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\CommonBundle\Exception\UnknownMethodException;
use Fitch\TutorBundle\Entity\File;
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
 * @Route("/file")
 */
class FileController extends Controller
{
    const AVATAR_WIDTH = 90;
    const AVATAR_HEIGHT = 150;
    const AVATAR_QUALITY = 90;

    /**
     * Updates a (simple) field on a file record
     *
     * @Route(
     *      "/update",
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
                ['file' => $file]
            ),
        ]);
    }

    /**
     * @Route("/stream/{id}", name="get_file_stream")
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
     * @Route("/avatar/{id}", name="get_file_as_avatar")
     * @Method("GET")
     * @Template()
     *
     * @param File $file
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function avatarAction(File $file)
    {


        $targ_w = $targ_h = 150;
        $jpeg_quality = 90;

        $src = 'gaufrette://tutor/'.$file->getFileSystemKey();
        $img_r = imagecreatefromjpeg($src);
        $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

        imagecopyresampled($dst_r,$img_r,0,0,0,0,
            $targ_w,$targ_h,$targ_w,$targ_h);

        header('Content-type: image/jpeg');
        imagejpeg($dst_r, null, $jpeg_quality);

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
     *      "/remove",
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
