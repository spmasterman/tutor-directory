<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Exception\UnknownMethodException;
use Fitch\TutorBundle\Model\FileManager;
use Fitch\TutorBundle\Model\FileTypeManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * File controller
 *
 * @Route("/file")
 */
class FileController extends Controller
{
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
     * @throws UnknownMethodException
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
}
