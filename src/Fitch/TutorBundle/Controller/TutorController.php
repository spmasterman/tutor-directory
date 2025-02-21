<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Form\Type\TutorType;
use Fitch\TutorBundle\Model\CountryManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
     *
     * @return array
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
     * @Route("/search", name="all_tutors", options={"expose"=true})
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

        $tutor = $tutorManager->createEntity();

        $form = $this->createCreateForm($tutor);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $tutorManager->saveEntity($tutor);

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
     *
     * @return array
     */
    public function newAction()
    {
        if (!$this->isGranted('ROLE_CAN_CREATE_TUTOR')) {
            throw new AccessDeniedHttpException('Unauthorised access!');
        }

        $tutor = $this->getTutorManager()->createEntity();
        $form   = $this->createCreateForm($tutor);

        return [
            'tutor' => $tutor ,
            'form'   => $form->createView(),
        ];
    }

    /**
     * @return TutorManagerInterface
     */
    private function getTutorManager()
    {
        return $this->get('fitch.manager.tutor');
    }

    /**
     * @return CountryManagerInterface
     */
    private function getCountryManager()
    {
        return $this->get('fitch.manager.country');
    }
}
