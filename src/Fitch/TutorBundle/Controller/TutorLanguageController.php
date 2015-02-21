<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\CommonBundle\Exception\UnknownMethodException;
use Fitch\TutorBundle\Model\LanguageManager;
use Fitch\TutorBundle\Model\TutorLanguageManager;
use Fitch\TutorBundle\Model\TutorManager;
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
     *      condition="
                request.request.has('pk') and request.request.get('pk') > 0
            and request.request.has('tutorLanguagePk')
            and request.request.has('name') and request.request.get('name') > ''
            and request.request.has('value')
        "
     * )
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
            $tutor = $this->getTutorManager()->findById($request->request->get('pk'));

            $name = $request->request->get('name');
            $name = preg_replace('/\d/', '', $name); // collections are numbered address1, address2 etc

            $value = $request->request->get('value');

            $tutorLanguageId = $request->request->get('tutorLanguagePk');
            if ($tutorLanguageId) {
                $tutorLanguage = $this->getTutorLanguageManager()->findById($tutorLanguageId);
            } else {
                $tutorLanguage = $this->getTutorLanguageManager()->createTutorLanguage();
                $tutor->addTutorLanguage($tutorLanguage);
            }

            switch ($name) {
                case 'tutor-language':
                    if ((string) (int) $value == $value) {
                        // if its an integer
                        $tutorLanguage->setLanguage($this->getLanguageManager()->findById((int) $value));
                    } else {
                        $tutorLanguage->setLanguage($this->getLanguageManager()->findOrCreate($value));
                    }
                    break;
                case 'tutor-language-note':
                    $tutorLanguage->setNote($value);
                    break;
                default :
                    throw new UnknownMethodException($name.' is not a valid Tutor Language member');
            }
            $this->getTutorManager()->saveTutor($tutor);
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
            $tutorLanguage = $this->getTutorLanguageManager()->findById($request->request->get('pk'));
            if (!$tutorLanguage) {
                throw new NotFoundHttpException('Tutor Language does not exist!');
            }

            $this->getTutorLanguageManager()->removeTutorLanguage($tutorLanguage->getId());
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
     * @return TutorLanguageManager
     */
    private function getTutorLanguageManager()
    {
        return $this->get('fitch.manager.tutor_language');
    }

    /**
     * @return LanguageManager
     */
    private function getLanguageManager()
    {
        return $this->get('fitch.manager.language');
    }

    /**
     * @return TutorManager
     */
    private function getTutorManager()
    {
        return $this->get('fitch.manager.tutor');
    }
}
