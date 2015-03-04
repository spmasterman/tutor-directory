<?php

namespace Fitch\FrontEndBundle\Controller;

use Fitch\UserBundle\Model\UserManager;
use Fitch\UserBundle\Model\UserManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class NavigationController
 */
class NavigationController extends Controller
{
    /**
     * @return array
     *
     * @Route("/mainbar", name="dashboard_mainbar")
     * @Template
     *
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
     *
     * @Method("GET")
     */
    public function sidebarAction()
    {
        return [
            'sidebarVisible' => $this->isGranted('ROLE_CAN_ACCESS_SIDEBAR'),
            'open' => $this->getUser()->isSideBarOpen(),
        ];
    }

    /**
     * @return array
     *
     * @Route("/toggle/sidebar", name="toggle_sidebar", options={"expose"=true})
     * @Template
     *
     * @Method("GET")
     */
    public function toggleSidebarAction()
    {
        $user = $this->getUser();
        $user->toggleSidebar();
        $this->getUserManager()->saveEntity($user);

        return new JsonResponse([]);
    }

    /**
     * @return UserManagerInterface
     */
    private function getUserManager()
    {
        return $this->get('fitch.manager.user');
    }
}
