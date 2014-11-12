<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Model\TutorManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tutor controller - most tutor interaction is expected to be via specific tailored pages - handled by the
 * ProfileController
 *
 * @Route()
 */
class TutorController extends Controller
{
    /**
     * Lists all Tutor entities.
     *
     * @Route("/", name="home")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        return array(
//            "dataTable" => $tutorDataTable,
        );
    }

    /**
     * Get all Tutor entities.
     *
     * @Route("/results", name="all_tutors", options={"expose"=true})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allAction()
    {
        return new JsonResponse([
            'data' => $this->getTutorManager()->populateTable()
        ]);
    }

    /**
     * @return TutorManager
     */
    private function getTutorManager()
    {
        return $this->get('fitch.manager.tutor');
    }

}
