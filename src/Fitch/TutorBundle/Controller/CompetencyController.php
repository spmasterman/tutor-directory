<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\CommonBundle\Exception\UnknownMethodException;
use Fitch\TutorBundle\Model\Competency\CompetencyUpdateFactory;
use Fitch\TutorBundle\Model\CompetencyManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Competency controller.
 *
 * @Route("/editor/competency")
 */
class CompetencyController extends Controller
{
    /**
     * Updates a competency field on a tutor record.
     *
     * @Route(
     *      "/update",
     *      name="competency_ajax_update",
     *      options={"expose"=true},
     *      condition="request.request.has('pk') and request.request.get('pk') > 0",
     *      condition="request.request.has('competencyPk')",
     *      condition="request.request.has('name') and request.request.get('name') > ''",
     *      condition="request.request.has('value')"
     * )
     *
     * @Method("POST")
     *
     * @param Request $request
     *
     * @throws UnknownMethodException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAction(Request $request)
    {
        try {
            // Grab what we need from the Request
            $tutor = $this->getTutorManager()->findById($request->request->get('pk'));
            $name = $request->request->get('name');
            $name = preg_replace('/\d/', '', $name); // collections are numbered address1, address2 etc
            $value = $request->request->get('value');

            // Pass it off to some UpdateHelper
            $competency = $this->getCompetencyManager()->findOrCreateCompetency(
                $request->request->get('competencyPk'),
                $tutor
            );
            $competencyUpdateHandler = CompetencyUpdateFactory::getUpdater($name, $this->container);
            $competencyUpdateHandler->update($competency, $value);

            // Save the entity
            $this->getTutorManager()->saveEntity($tutor);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
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
                    'isEditor' => $this->isGranted('ROLE_CAN_EDIT_TUTOR'),
                    'isAdmin' => $this->isGranted('ROLE_CAN_CREATE_LOOKUP_VALUES'),
                ]
            ),
        ]);
    }

    /**
     * @Route(
     *      "/remove",
     *      name="competency_ajax_remove",
     *      options={"expose"=true},
     *      condition="request.request.has('pk') and request.request.get('pk') > 0"
     * )
     *
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
            if (!$competency) {
                throw new NotFoundHttpException('Competency does not exist!');
            }

            $this->getCompetencyManager()->removeCompetency($competency->getId());
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'success' => true,
        ]);
    }

    /**
     * @return CompetencyManagerInterface
     */
    private function getCompetencyManager()
    {
        return $this->get('fitch.manager.competency');
    }

    /**
     * @return TutorManagerInterface
     */
    private function getTutorManager()
    {
        return $this->get('fitch.manager.tutor');
    }
}
