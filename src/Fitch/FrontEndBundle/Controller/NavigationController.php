<?php

namespace Fitch\FrontEndBundle\Controller;

use Fitch\UserBundle\Model\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    /**
     * @return array
     *
     * @Route("/sidemenu", name="dashboard_side_menu")
     * @Template
     * @Method("GET")
     */
    public function sidemenuAction()
    {
        return [
            'open' => $this->getUser()->isSideBarOpen()
        ];
    }

    /**
     * @return array
     *
     * @Route("/toggle/menu", name="toggle_side_menu", options={"expose"=true})
     * @Template
     * @Method("GET")
     */
    public function toggleSidebarAction()
    {
        $user = $this->getUser();
        $user->toggleSidebar();
        $this->getUserManager()->saveUser($user);
        return new JsonResponse([]);
    }

    /**
     * @return UserManager
     */
    public function getUserManager()
    {
        return $this->get('fitch.manager.user');
    }
}
