<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Form\Type\TutorType;
use Fitch\TutorBundle\Model\AddressManager;
use Fitch\TutorBundle\Model\CountryManager;
use Fitch\TutorBundle\Model\OperatingRegionManager;
use Fitch\TutorBundle\Model\StatusManager;
use Fitch\TutorBundle\Model\TutorManager;
use Fitch\TutorBundle\Model\TutorTypeManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tutor controller - most tutor interaction is expected to be via specific tailored pages - handled by the
 * ProfileController.
 */
class TutorController extends Controller
{
    /**
     * Lists all Tutor entities.
     *
     * @Route("", name="home")
     *
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $features = [];

        // If you can add tutors, add n add tutor button
        if ($this->isGranted('ROLE_CAN_CREATE_TUTOR')) {
            $features[] = 'add_tutor';
        }

        // If you cant see the sidebar (and therefore access the reports menu)
        if (!$this->isGranted('ROLE_CAN_ACCESS_SIDEBAR')) {
            //add a link to the reports page
            if ($this->isGranted('ROLE_CAN_CREATE_AD_HOC_REPORTS')) {
                $features[] = 'advanced_report';
            }

            // If you cant create reports, dont have the side menu, but still need to be able to view reports
            // we best show a list...
            if (count($features) == 0 && $this->isGranted('ROLE_CAN_VIEW_SAVED_REPORTS')) {
                $features[] = 'list_of_reports';
            }
        }

        return [
            'features' => $features,
        ];
    }

    /**
     * Get all Tutor entities.
     *
     * @Route("/results", name="all_tutors", options={"expose"=true})
     *
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allAction()
    {
        $tableData = $this->getTutorManager()->populateTable();

        return new JsonResponse([
            'data' => $tableData,
        ]);
    }

    /**
     * Creates a new Tutor entity.
     *
     * @Route("/", name="tutor_create")
     *
     * @Method("POST")
     * @Template("FitchTutorBundle:Tutor:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('ROLE_CAN_CREATE_TUTOR')) {
            throw new AccessDeniedHttpException('Unauthorised access!');
        }

        $tutorManager = $this->getTutorManager();

        $tutor = $tutorManager->createTutor(
            $this->getAddressManager(),
            $this->getCountryManager(),
            $this->getStatusManager(),
            $this->getOperatingRegionManager(),
            $this->getTutorTypeManager()
        );

        $form = $this->createCreateForm($tutor);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $tutorManager->saveTutor($tutor);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('tutor.new.success')
            );

            return $this->redirectToRoute('tutor_profile', ['id' => $tutor->getId()]);
        }

        return [
            'tutor' => $tutor,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Tutor entity.
     *
     * @param Tutor $tutor The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Tutor $tutor)
    {
        $form = $this->createForm(new TutorType($this->get('translator'), $this->getCountryManager()), $tutor, [
            'action' => $this->generateUrl('tutor_create'),
            'method' => 'POST',
        ]);

        $form->add(
            'submit',
            'submit',
            [
                'label' => 'Create',
                'attr' => [
                    'submit_class' => 'btn-success',
                    'submit_glyph' => 'fa-plus-circle',
                ],
            ]
        );

        return $form;
    }

    /**
     * Displays a form to create a new Tutor entity.
     *
     * @Route("/new", name="tutor_new")
     *
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        if (!$this->isGranted('ROLE_CAN_CREATE_TUTOR')) {
            throw new AccessDeniedHttpException('Unauthorised access!');
        }

        $tutor = $this->getTutorManager()->createTutor(
            $this->getAddressManager(),
            $this->getCountryManager(),
            $this->getStatusManager(),
            $this->getOperatingRegionManager(),
            $this->getTutorTypeManager()
        );
        $form   = $this->createCreateForm($tutor);

        return [
            'tutor' => $tutor ,
            'form'   => $form->createView(),
        ];
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
     * @return CountryManager
     */
    private function getCountryManager()
    {
        return $this->get('fitch.manager.country');
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
}
