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
     * @Route("/mainbar", name="dashboard_mainbar")
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
     * @Route("/sidebar", name="dashboard_sidebar")
     * @Template
     * @Method("GET")
     */
    public function sidebarAction()
    {
        return [
            'sidebarVisible' => $this->isGranted('ROLE_FULL_EDITOR'),
            'open' => $this->getUser()->isSideBarOpen()
        ];
    }

    /**
     * @return array
     *
     * @Route("/toggle/sidebar", name="toggle_sidebar", options={"expose"=true})
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
    private function getUserManager()
    {
        return $this->get('fitch.manager.user');
    }
}
