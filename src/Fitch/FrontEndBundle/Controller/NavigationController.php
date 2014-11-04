<?php

namespace Fitch\FrontEndBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NavigationController extends Controller
{

    /**
     * @return array
     *
     * @Route("/mainbar", name="dashboard_main_bar")
     * @Template
     * @Method("GET")
     */
    public function mainbarAction()
    {
        return [];
    }
}
