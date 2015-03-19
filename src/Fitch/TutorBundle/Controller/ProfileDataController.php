<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\CommonBundle\Entity\NamedEntityInterface;
use Fitch\TutorBundle\Model\CategoryManagerInterface;
use Fitch\TutorBundle\Model\CompetencyLevelManagerInterface;
use Fitch\TutorBundle\Model\CompetencyTypeManagerInterface;
use Fitch\TutorBundle\Model\CountryManagerInterface;
use Fitch\TutorBundle\Model\FileTypeManagerInterface;
use Fitch\TutorBundle\Model\LanguageManagerInterface;
use Fitch\TutorBundle\Model\OperatingRegionManagerInterface;
use Fitch\TutorBundle\Model\ProficiencyManagerInterface;
use Fitch\TutorBundle\Model\StatusManagerInterface;
use Fitch\TutorBundle\Model\TutorTypeManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Tutor Profile controller - data for Ajax setup.
 *
 * @Route("/profile")
 */
class ProfileDataController extends Controller
{
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
     * @param int $tutorId
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

        $extractName = function (NamedEntityInterface $object) {
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
     * @return CompetencyTypeManagerInterface
     */
    private function getCompetencyTypeManager()
    {
        return $this->get('fitch.manager.competency_type');
    }

    /**
     * @return CompetencyLevelManagerInterface
     */
    private function getCompetencyLevelManager()
    {
        return $this->get('fitch.manager.competency_level');
    }

    /**
     * @return LanguageManagerInterface
     */
    private function getLanguageManager()
    {
        return $this->get('fitch.manager.language');
    }

    /**
     * @return CountryManagerInterface
     */
    private function getCountryManager()
    {
        return $this->get('fitch.manager.country');
    }

    /**
     * @return StatusManagerInterface
     */
    private function getStatusManager()
    {
        return $this->get('fitch.manager.status');
    }

    /**
     * @return OperatingRegionManagerInterface
     */
    private function getOperatingRegionManager()
    {
        return $this->get('fitch.manager.operating_region');
    }

    /**
     * @return TutorTypeManagerInterface
     */
    private function getTutorTypeManager()
    {
        return $this->get('fitch.manager.tutor_type');
    }

    /**
     * @return ProficiencyManagerInterface
     */
    private function getProficiencyManager()
    {
        return $this->get('fitch.manager.proficiency');
    }

    /**
     * @return CategoryManagerInterface
     */
    private function getCategoryManager()
    {
        return $this->get('fitch.manager.category');
    }

    /**
     * @return FileTypeManagerInterface
     */
    private function getFileTypeManager()
    {
        return $this->get('fitch.manager.file_type');
    }
}
