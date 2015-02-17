<?php

namespace Fitch\TutorBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Report controller - manages a filterable, savable report that is a little more dynamic than the searchable table
 * on the front page....
 *
 * @Route("/report")
 */
class ReportController extends Controller
{

    /**
     * Lists all Tutor entities.
     *
     * @Route("", name="list")
     * @Method("GET")
     *
     * @Template()
     */
    public function listAction()
    {
        return [];
    }
}