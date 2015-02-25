<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\NamedTraitInterface;
use Fitch\CommonBundle\Exception\ClassNotFoundException;
use Fitch\CommonBundle\Exception\UnknownMethodException;
use Fitch\TutorBundle\Entity\Note;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\AddressManager;
use Fitch\TutorBundle\Model\CategoryManager;
use Fitch\TutorBundle\Model\CompetencyLevelManager;
use Fitch\TutorBundle\Model\CompetencyTypeManager;
use Fitch\TutorBundle\Model\CountryManager;
use Fitch\TutorBundle\Model\CurrencyManager;
use Fitch\TutorBundle\Model\EmailManager;
use Fitch\TutorBundle\Model\FileTypeManager;
use Fitch\TutorBundle\Model\LanguageManager;
use Fitch\TutorBundle\Model\NoteManager;
use Fitch\TutorBundle\Model\OperatingRegionManager;
use Fitch\TutorBundle\Model\PhoneManager;
use Fitch\TutorBundle\Model\ProficiencyManager;
use Fitch\TutorBundle\Model\Profile\ProfileUpdateFactory;
use Fitch\TutorBundle\Model\RateManager;
use Fitch\TutorBundle\Model\StatusManager;
use Fitch\TutorBundle\Model\TutorManager;
use Fitch\TutorBundle\Model\TutorTypeManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tutor Profile controller.
 *
 * @Route("/profile")
 */
class ProfileController extends Controller
{
    /**
     * Finds and displays a Tutor entity.
     *
     * @Route("/{id}/{tab}", requirements={"id" = "\d+"}, name="tutor_profile", options={"expose"=true})
     *
     * @Method("GET")
     * @Template()
     *
     * @param Tutor $tutor
     * @param $tab
     *
     * @return array
     */
    public function showAction(Tutor $tutor, $tab = 'profile')
    {
        return [
            'tutor' => $tutor,
            'user' => $this->getUser(),
            'tab' => $tab,
            'rateManager' => $this->getRateManager(),
            'isEditor' => $this->isGranted('ROLE_CAN_EDIT_TUTOR'),
            'isAdmin' => $this->isGranted('ROLE_CAN_ACCESS_SENSITIVE_DATA'),
        ];
    }

    /**
     * Updates a (simple) field on a tutor record.
     *
     * @Route(
     *      "/update",
     *      name="tutor_ajax_update",
     *      options={"expose"=true},
     *      condition="request.request.has('pk') and request.request.get('pk') > 0",
     *      condition="request.request.has('name') and request.request.get('name') > '' ",
     *      condition="request.request.has('value')"
     * )
     *
     * @Method("POST")
     *
     * @param Request $request
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

            try {
                $profileUpdateHandler = ProfileUpdateFactory::getUpdater($name, $this->container);
                $relatedEntity = $profileUpdateHandler->update($request, $tutor, $value);
            } catch (ClassNotFoundException $e) {
                // try a simple field...
                $setter = 'set'.ucfirst($name);
                if (is_callable([$tutor, $setter])) {
                    $tutor->$setter($value);
                } else {
                    throw new UnknownMethodException($setter.' is not a valid Tutor method');
                }
                $relatedEntity = null;
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
            'id' => $relatedEntity instanceof IdentityTraitInterface ? $relatedEntity->getId() : null,
            'detail' => $relatedEntity instanceof Note ? $relatedEntity->getProvenance() : null,
        ]);
    }

    /*
     * The reason that some of these controllers are defined here, rather than in the (say) the LanguageController file
     * is because the LanguageController file is in the /admin/ url space, and therefore routes defined in are not
     * available to editors etc.
     *
     * These routes are only used by javascript UI elements on the profile page so - here works well enough from a
     * security perspective...
     */

    /**
     * Returns all active languages as a JSON Array - suitable for use in "select"
     * style lists, with a preferred section.
     *
     * @Route("/active/language", name="active_languages")
     *
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function activeLanguagesAction()
    {
        return new JsonResponse($this->getLanguageManager()->buildGroupedChoices());
    }

    /**
     * Returns all active languages as a JSON Array - suitable for use in "select"
     * style lists, with a preferred section.
     *
     * @Route("/active/proficiency", name="active_proficiencies")
     *
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function activeProficienciesAction()
    {
        return new JsonResponse($this->getProficiencyManager()->buildGroupedChoices());
    }

    /**
     * Returns all active competencyTypes as a JSON Array.
     *
     * @Route("/active/competency/type", name="active_competency_type")
     *
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function activeCompetencyTypesAction()
    {
        return new JsonResponse($this->getCompetencyTypeManager()->buildGroupedChoices($this->getCategoryManager()));
    }

    /**
     * Returns all active competencyLevels as a JSON Array.
     *
     * @Route("/active/competency/level", name="active_competency_level")
     *
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function activeCompetencyLevelsAction()
    {
        return new JsonResponse($this->getCompetencyLevelManager()->buildGroupedChoices());
    }


    /**
     * Returns the regions as a JSON Array.
     *
     * @Route("/active/region", name="all_regions")
     *
     * @Method("GET")
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function activeOperatingRegionAction()
    {
        return new JsonResponse($this->getOperatingRegionManager()->buildGroupedChoices());
    }


    /**
     * Returns the tutor_types as a JSON Array.
     *
     * @Route("/active/tutor_type", name="all_tutor_types")
     *
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function activeTutorTypeAction()
    {
        return new JsonResponse($this->getTutorTypeManager()->buildGroupedChoices());
    }

    /**
     * Returns the statuses as a JSON Array.
     *
     * @Route("/active/status", name="all_status")
     *
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function activeStatusAction()
    {
        return new JsonResponse($this->getStatusManager()->buildGroupedChoices());
    }


    /**
     * Returns the countries as a JSON Array.
     *
     * @Route("/active/file_type", name="all_file_types", options={"expose"=true})
     *
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function activeFileTypeAction()
    {
        return new JsonResponse($this->getFileTypeManager()->buildGroupedChoices());
    }


    /**
     * Returns a JSON object that contains all the lookup values required to drive the Profile page
     * this is thrown into one large-ish controller so that only a single request is made to the server
     * (rather than each element being requested individually at page-load).
     *
     * Also returned are prototype "New Rows" suitable for inserting into the DOM
     *
     * @Route("/prototype/{tutorId}", name="profile_dynamic_data", options={"expose"=true})
     *
     * @Method("GET")
     *
     * @param $tutorId
     *
     * @return JsonResponse
     */
    public function prototypeAction($tutorId)
    {
        $isEditor = $this->isGranted('ROLE_CAN_EDIT_TUTOR');
        $isAdmin = $this->isGranted('ROLE_CAN_CREATE_LOOKUP_VALUES');

        $options = [
            'prototype' => true,
            'tutorId' => $tutorId,
            'isEditor' => $isEditor,
            'isAdmin' => $isAdmin,
        ];

        $extractName = function (NamedTraitInterface $object) {
            return $object->getName();
        };

        return new JsonResponse([
            'groupedCountries' => $this->getCountryManager()->buildGroupedChoices(),
            'allCompetencyTypes' => array_map($extractName, $this->getCompetencyTypeManager()->findAll()),
            'allCompetencyLevels' => array_map($extractName, $this->getCompetencyLevelManager()->findAll()),
            'allLanguages' => array_map($extractName, $this->getLanguageManager()->findAll()),
            'allProficiencies' => array_map($extractName, $this->getProficiencyManager()->findAll()),
            'languagePrototype' => $this->renderView("FitchTutorBundle:Profile:language_row.html.twig", $options),
            'competencyPrototype' => $this->renderView("FitchTutorBundle:Profile:competency_row.html.twig", $options),
            'addressPrototype' => $this->renderView("FitchTutorBundle:Profile:address_row.html.twig", $options),
            'emailPrototype' => $this->renderView("FitchTutorBundle:Profile:email_row.html.twig", $options),
            'phonePrototype' => $this->renderView("FitchTutorBundle:Profile:phone_row.html.twig", $options),
            'notePrototype' => $this->renderView("FitchTutorBundle:Profile:note_row.html.twig", $options),
            'ratePrototype' => $this->renderView("FitchTutorBundle:Profile:rate_row.html.twig", $options),
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
     * @return CompetencyLevelManager
     */
    private function getCompetencyLevelManager()
    {
        return $this->get('fitch.manager.competency_level');
    }

    /**
     * @return LanguageManager
     */
    private function getLanguageManager()
    {
        return $this->get('fitch.manager.language');
    }

    /**
     * @return CountryManager
     */
    private function getCountryManager()
    {
        return $this->get('fitch.manager.country');
    }

    /**
     * @return TutorManager
     */
    private function getTutorManager()
    {
        return $this->get('fitch.manager.tutor');
    }

    /**
     * @return AddressManager
     */
    private function getAddressManager()
    {
        return $this->get('fitch.manager.address');
    }

    /**
     * @return EmailManager
     */
    private function getEmailManager()
    {
        return $this->get('fitch.manager.email');
    }

    /**
     * @return PhoneManager
     */
    private function getPhoneManager()
    {
        return $this->get('fitch.manager.phone');
    }

    /**
     * @return RateManager
     */
    private function getRateManager()
    {
        return $this->get('fitch.manager.rate');
    }

    /**
     * @return CurrencyManager
     */
    private function getCurrencyManager()
    {
        return $this->get('fitch.manager.currency');
    }

    /**
     * @return NoteManager
     */
    private function getNoteManager()
    {
        return $this->get('fitch.manager.note');
    }

    /**
     * @return StatusManager
     */
    private function getStatusManager()
    {
        return $this->get('fitch.manager.status');
    }

    /**
     * @return OperatingRegionManager
     */
    private function getOperatingRegionManager()
    {
        return $this->get('fitch.manager.operating_region');
    }

    /**
     * @return TutorTypeManager
     */
    private function getTutorTypeManager()
    {
        return $this->get('fitch.manager.tutor_type');
    }

    /**
     * @return ProficiencyManager
     */
    private function getProficiencyManager()
    {
        return $this->get('fitch.manager.proficiency');
    }

    /**
     * @return CategoryManager
     */
    private function getCategoryManager()
    {
        return $this->get('fitch.manager.category');
    }

    /**
     * @return FileTypeManager
     */
    private function getFileTypeManager()
    {
        return $this->get('fitch.manager.file_type');
    }
}
