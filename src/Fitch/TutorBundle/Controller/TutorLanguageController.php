<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\CommonBundle\Exception\UnknownMethodException;
use Fitch\TutorBundle\Model\TutorLanguage\TutorLanguageUpdateFactory;
use Fitch\TutorBundle\Model\TutorLanguageManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * TutorLanguage controller.
 *
 * @Route("/editor/tutor/language")
 */
class TutorLanguageController extends Controller
{
    /**
     * Updates a TutorLanguage field on a tutor record.
     *
     * @Route(
     *      "/update",
     *      name="tutor_language_ajax_update",
     *      options={"expose"=true},
     *      condition="request.request.has('pk') and request.request.get('pk') > 0",
     *      condition="request.request.has('tutorLanguagePk')",
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
            $tutorLanguage = $this->getTutorLanguageManager()->findOrCreateTutorLanguage(
                $request->request->get('tutorLanguagePk'),
                $tutor
            );
            $tutorLanguageUpdateHandler = TutorLanguageUpdateFactory::getUpdater($name, $this->container);
            $tutorLanguageUpdateHandler->update($tutorLanguage, $value);

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
            'id' => $tutorLanguage->getId(),
            'renderedTutorLanguageRow' => $this->renderView(
                'FitchTutorBundle:Profile:language_row.html.twig',
                [
                    'tutor' => $tutor,
                    'tutorLanguage' => $tutorLanguage,
                    'isEditor' => $this->isGranted('ROLE_CAN_EDIT_TUTOR'),
                    'isAdmin' => $this->isGranted('ROLE_CAN_CREATE_LOOKUP_VALUES'),
                ]
            ),
        ]);
    }

    /**
     * @Route(
     *      "/remove",
     *      name="tutor_language_ajax_remove",
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
            $tutorLanguage = $this->getTutorLanguageManager()->findById($request->request->get('pk'));
            if (!$tutorLanguage) {
                throw new NotFoundHttpException('Tutor Language does not exist!');
            }

            $this->getTutorLanguageManager()->removeEntity($tutorLanguage);
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
     * @return TutorLanguageManagerInterface
     */
    private function getTutorLanguageManager()
    {
        return $this->get('fitch.manager.tutor_language');
    }

    /**
     * @return TutorManagerInterface
     */
    private function getTutorManager()
    {
        return $this->get('fitch.manager.tutor');
    }
}
