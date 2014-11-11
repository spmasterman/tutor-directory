<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Model\CompetencyLevelManager;
use Fitch\TutorBundle\Model\CompetencyTypeManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Competency controller
 *
 * @Route("/competency")
 */
class CompetencyController extends Controller
{
    /**
     * Returns the countries as a JSON Array
     *
     * @Route("/lookups", name="competency_lookups", options={"expose"=true})
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */

    public function lookupAction(){
        $types = [];
        foreach($this->getCompetencyTypeManager()->findAll() as $type) {
            $types[] = [
                'value' => $type->getId(),
                'text' => $type->getName(),
            ];
        }

        $levels = [];
        foreach($this->getCompetencyLevelManager()->findAll() as $level) {
            $levels[] = [
                'value' => $level->getId(),
                'text' => $level->getName(),
            ];
        }

        return new JsonResponse([
            'type' => $types,
            'level' => $levels
        ]);
    }


//    /**
//     * @Route(
//     *      "/remove",
//     *      name="competency_ajax_remove",
//     *      options={"expose"=true},
//     *      condition="
//                request.request.has('pk') and request.request.get('pk') > 0
//            "
//     * )
//     * @Method("POST")
//     * @Template()
//     *
//     * @param Request $request
//     *
//     * @return \Symfony\Component\HttpFoundation\JsonResponse
//     */
//    public function removeAction(Request $request)
//    {
//        try {
//            $fileEntity = $this->getFileManager()->findById($request->request->get('pk'));
//
//            $fileSystem = $this->getFileSystemMapService()->get('tutor');
//            $file = $fileSystem->get($fileEntity->getFileSystemKey());
//
//            if(!$file) {
//                throw new NotFoundHttpException('File does not exist!');
//            }
//
//            $file->delete();
//            $this->getFileManager()->removeFile($fileEntity->getId());
//        } catch (Exception $e) {
//            return new JsonResponse([
//                'success' => false,
//                'message' => $e->getMessage()
//            ], Response::HTTP_BAD_REQUEST);
//        }
//
//        return new JsonResponse([
//            'success' => true,
//        ]);
//    }

    /**
     * @return CompetencyTypeManager
     */
    private function getCompetencyTypeManager()
    {
        return $this->get('fitch.manager.competency_type');
    }

    /**
     * @return CompetencyLevelManager
     */
    private function getCompetencyLevelManager()
    {
        return $this->get('fitch.manager.competency_level');
    }

}
