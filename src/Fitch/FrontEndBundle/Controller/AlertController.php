<?php

namespace Fitch\FrontEndBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AlertController extends Controller
{
    /**
     * @Route("/index")
     * @Template()
     * @Method("GET")
     */
    public function indexAction()
    {
        return array(
            'message' => '...message...',
            'display' => false
        );
    }
}
