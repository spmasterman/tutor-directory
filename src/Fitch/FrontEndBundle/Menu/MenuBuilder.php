<?php

namespace Fitch\FrontEndBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\Role\SwitchUserRole;

class MenuBuilder extends ContainerAware
{
    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function sidebarMenu(FactoryInterface $factory, array $options)
    {

        $menu = $factory
            ->createItem('root', ['route' => 'home'])
            ->setChildrenAttribute('class', 'main-menu')
        ;

        $this
            ->addTutorTypes($menu)
            ->addStatus($menu)
            ->addRegions($menu)
            ->addCountries($menu)
            ->addCurrencies($menu)
            ->addFileTypes($menu)
            ->addCompetencyTypes($menu)
            ->addCompetencyLevels($menu)
            ->addUsers($menu)
        ;

        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function breadcrumbMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory
            ->createItem('root', ['route' => 'home'])
            ->setAttribute('class', 'breadcrumb')
        ;

        $homeNode = $menu
            ->addChild('menu.home', ['route' => 'home'])
            ->setExtra('translation_domain', 'FitchFrontEndBundle')
            ->setAttribute('icon', 'fa fa-home fa-fw');

        $this
            ->addTutorTypes($homeNode)
            ->addStatus($homeNode)
            ->addRegions($homeNode)
            ->addCountries($homeNode)
            ->addCurrencies($homeNode)
            ->addFileTypes($homeNode)
            ->addCompetencyTypes($homeNode)
            ->addCompetencyLevels($homeNode)
            ->addUsers($homeNode)
        ;

        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function userMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root')
                        ->setChildrenAttribute('class', 'dropdown-menu')
                        ->setChildrenAttribute('role', 'menu');

        $menu->addChild('menu.user.profile', array('route' => 'fos_user_profile_show'))
                ->setAttribute('icon', 'fa fa-user fa-fw')
                ->setExtra('translation_domain', 'FitchFrontEndBundle')
                ->getParent()
             ->addChild('menu.user.logout', array('route' => 'fos_user_security_logout'))
                ->setAttribute('icon', 'fa fa-power-off fa-fw')
                ->setExtra('translation_domain', 'FitchFrontEndBundle')
                ->getParent();

        $security = $this->container->get('security.context');
        if ($security->isGranted('ROLE_PREVIOUS_ADMIN')) {
            $originalUser = $security->getToken();
            foreach ($security->getToken()->getRoles() as $role) {
                if ($role instanceof SwitchUserRole) {
                    $originalUser = $role->getSource();
                    break;
                }
            }

            $menu->addChild($originalUser->getUsername(), [
                    'route' => 'home',
                    'routeParameters' => [
                        '_impersonate' => '_exit'
                    ]
                ])
                ->setAttribute('icon', 'fa fa-eject fa-fw')
                ->setExtra('translation_domain', 'FitchFrontEndBundle')
                ->getParent();
        }

        return $menu;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return MenuBuilder
     */
    private function addTutorTypes(ItemInterface $menu)
    {
        if (false !== $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $menu
                ->addChild('menu.tutor_type', array('route' => 'tutor_type'))
                ->setAttribute('icon', 'fa fa-child fa-fw')
                ->setExtra('translation_domain', 'FitchFrontEndBundle')
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return MenuBuilder
     */
    private function addStatus(ItemInterface $menu)
    {
        if (false !== $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $menu
                ->addChild('menu.tutor_status', array('route' => 'status'))
                ->setAttribute('icon', 'fa fa-tag fa-fw')
                ->setExtra('translation_domain', 'FitchFrontEndBundle')
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return MenuBuilder
     */
    private function addRegions(ItemInterface $menu)
    {
        if (false !== $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $menu
                ->addChild('menu.regions', array('route' => 'region'))
                ->setAttribute('icon', 'fa fa-globe fa-fw')
                ->setExtra('translation_domain', 'FitchFrontEndBundle')
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return MenuBuilder
     */
    private function addCountries(ItemInterface $menu)
    {
        if (false !== $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $menu
                ->addChild('menu.countries', array('route' => 'country'))
                ->setAttribute('icon', 'fa fa-flag-o fa-fw')
                ->setExtra('translation_domain', 'FitchFrontEndBundle')
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return MenuBuilder
     */
    private function addCurrencies(ItemInterface $menu)
    {
        if (false !== $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $menu
                ->addChild('menu.currencies', array('route' => 'currency'))
                ->setAttribute('icon', 'fa fa-money fa-fw')
                ->setExtra('translation_domain', 'FitchFrontEndBundle')
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return MenuBuilder
     */
    private function addFileTypes(ItemInterface $menu)
    {
        if (false !== $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $menu
                ->addChild('menu.file_type', array('route' => 'file_type'))
                ->setAttribute('icon', 'fa fa-files-o fa-fw')
                ->setExtra('translation_domain', 'FitchFrontEndBundle')
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return MenuBuilder
     */
    private function addCompetencyTypes(ItemInterface $menu)
    {
        if (false !== $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $menu
                ->addChild('menu.competency_type', array('route' => 'competency_type'))
                ->setAttribute('icon', 'fa fa-bullseye fa-fw')
                ->setExtra('translation_domain', 'FitchFrontEndBundle')
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return MenuBuilder
     */
    private function addCompetencyLevels(ItemInterface $menu)
    {
        if (false !== $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $menu
                ->addChild('menu.competency_level', array('route' => 'competency_level'))
                ->setAttribute('icon', 'fa fa-signal fa-fw')
                ->setExtra('translation_domain', 'FitchFrontEndBundle')
            ;
        }

        return $this;
    }

    private function addUsers(ItemInterface $menu)
    {
        if (false !== $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $menu
                ->addChild('menu.user_management', array('route' => 'user'))
                ->setAttribute('icon', 'fa fa-users fa-fw')
                ->setExtra('translation_domain', 'FitchFrontEndBundle')
            ;
        }

        return $this;
    }
}
