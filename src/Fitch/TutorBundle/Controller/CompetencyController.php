<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Exception\UnknownMethodException;
use Fitch\TutorBundle\Model\CompetencyLevelManager;
use Fitch\TutorBundle\Model\CompetencyManager;
use Fitch\TutorBundle\Model\CompetencyTypeManager;
use Fitch\TutorBundle\Model\TutorManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Competency controller
 *
 * @Route("/editor/competency")
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
            $types[] = $type->getName();
        }

        $levels = [];
        foreach($this->getCompetencyLevelManager()->findAll() as $level) {
            $levels[] = $level->getName();
        }

        return new JsonResponse([
            'type' => $types,
            'level' => $levels
        ]);
    }

    /**
     * Updates a competency field on a tutor record
     *
     * @Route(
     *      "/update",
     *      name="competency_ajax_update",
     *      options={"expose"=true},
     *      condition="
                request.request.has('pk') and request.request.get('pk') > 0
            and request.request.has('competencyPk')
            and request.request.has('name') and request.request.get('name') > ''
            and request.request.has('value')
        "
     * )
     * @Method("POST")
     *
     * @param Request $request
     *
     * @throws UnknownMethodException
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAction(Request $request)
    {
        try {
            $tutor = $this->getTutorManager()->findById($request->request->get('pk'));

            $name = $request->request->get('name');
            $name = preg_replace('/\d/', '', $name); // collections are numbered address1, address2 etc

            $value = $request->request->get('value');

            $competencyId = $request->request->get('competencyPk');
            if ($competencyId) {
                $competency = $this->getCompetencyManager()->findById($competencyId);
            } else {
                $competency = $this->getCompetencyManager()->createCompetency();
                $tutor->addCompetency($competency);
            }

            switch ($name) {
                case 'competency-level':
                    $competency->setCompetencyLevel($this->getCompetencyLevelManager()->findOrCreate($value));
                    break;
                case 'competency-type':
                    $competency->setCompetencyType($this->getCompetencyTypeManager()->findOrCreate($value));
                    break;
                case 'competency-note':
                    $competency->setNote($value);
                    break;
                default :
                    throw new UnknownMethodException($name . ' is not a valid Competency member');
            }
            $this->getTutorManager()->saveTutor($tutor);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'success' => true,
            'id' => $competency->getId(),
            'renderedCompetencyRow' => $this->renderView(
                'FitchTutorBundle:Profile:competency_row.html.twig',
                [
                    'tutor' => $tutor,
                    'competency' => $competency,
                    'editor' => (bool) $this->get('security.context')->isGranted('ROLE_EDITOR')
                ]
            ),
        ]);
    }

    /**
     * @Route(
     *      "/remove",
     *      name="competency_ajax_remove",
     *      options={"expose"=true},
     *      condition="
                request.request.has('pk') and request.request.get('pk') > 0
            "
     * )
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeAction(Request $request)
    {
        try {
            $competency = $this->getCompetencyManager()->findById($request->request->get('pk'));
            if(!$competency) {
                throw new NotFoundHttpException('Competency does not exist!');
            }

            $this->getCompetencyManager()->removeCompetency($competency->getId());
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
     * @return CompetencyTypeManager
     */
    private function getCompetencyTypeManager()
    {
        return $this->get('fitch.manager.competency_type');
    }

    /**
     * @return CompetencyManager
     */
    private function getCompetencyManager()
    {
        return $this->get('fitch.manager.competency');
    }

    /**
     * @return CompetencyLevelManager
     */
    private function getCompetencyLevelManager()
    {
        return $this->get('fitch.manager.competency_level');
    }

    /**
     * @return TutorManager
     */
    private function getTutorManager()
    {
        return $this->get('fitch.manager.tutor');
    }
}
