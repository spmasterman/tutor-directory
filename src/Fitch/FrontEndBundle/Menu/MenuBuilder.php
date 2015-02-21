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
     * @param array            $options
     *
     * @return ItemInterface
     */
    public function sidebarMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory
            ->createItem('root', ['route' => 'home'])
            ->setChildrenAttribute('class', 'main-menu')
        ;

        $this
            ->addReports($menu)
            ->addTutorTypes($menu)
            ->addStatus($menu)
            ->addRegions($menu)
            ->addCountries($menu)
            ->addCurrencies($menu)
            ->addLanguages($menu)
            ->addFileTypes($menu)
            ->addCategory($menu)
            ->addCompetencyTypes($menu)
            ->addCompetencyLevels($menu)
            ->addUsers($menu)
        ;

        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     * @param array            $options
     *
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
            ->setExtra('translation_domain', 'menu')
            ->setAttribute('icon', 'fa fa-home fa-fw');

        $this
            ->addReports($homeNode)
            ->addTutorTypes($homeNode)
            ->addStatus($homeNode)
            ->addRegions($homeNode)
            ->addCountries($homeNode)
            ->addCurrencies($homeNode)
            ->addLanguages($homeNode)
            ->addFileTypes($homeNode)
            ->addCategory($homeNode)
            ->addCompetencyTypes($homeNode)
            ->addCompetencyLevels($homeNode)
            ->addUsers($homeNode)
            ->addProfile($homeNode)
        ;

        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return ItemInterface
     */
    public function userMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root')
                        ->setChildrenAttribute('class', 'dropdown-menu')
                        ->setChildrenAttribute('role', 'menu');

        $menu->addChild('menu.user.profile', ['route' => 'fos_user_profile_show'])
                ->setAttribute('icon', 'fa fa-user fa-fw')
                ->setExtra('translation_domain', 'menu')
                ->getParent()
             ->addChild('menu.user.logout', ['route' => 'fos_user_security_logout'])
                ->setAttribute('icon', 'fa fa-power-off fa-fw')
                ->setExtra('translation_domain', 'menu')
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
                        '_impersonate' => '_exit',
                    ],
                ])
                ->setAttribute('icon', 'fa fa-eject fa-fw')
                ->setExtra('translation_domain', 'menu')
                ->getParent();
        }

        return $menu;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return $this
     */
    private function addReports(ItemInterface $menu)
    {
        if ($this->isGranted('ROLE_CAN_CREATE_AD_HOC_REPORTS')) {
            $menu
                ->addChild('menu.report', ['route' => 'report_header'])
                ->setAttribute('icon', 'fa fa-list-alt fa-fw')
                ->setExtra('translation_domain', 'menu')
                ->setExtra('routes', [
                    ['pattern' => '/report/'],
                ])
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return $this
     */
    private function addTutorTypes(ItemInterface $menu)
    {
        if ($this->isGranted('ROLE_CAN_EDIT_LOOKUP_VALUES')) {
            $menu
                ->addChild('menu.tutor_type', ['route' => 'tutor_type'])
                ->setAttribute('icon', 'fa fa-child fa-fw')
                ->setExtra('translation_domain', 'menu')
                ->setExtra('routes', [
                    ['pattern' => '/tutor_type/'],
                ])
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return $this
     */
    private function addCategory(ItemInterface $menu)
    {
        if ($this->isGranted('ROLE_CAN_EDIT_LOOKUP_VALUES')) {
            $menu
                ->addChild('menu.category', ['route' => 'category'])
                ->setAttribute('icon', 'fa fa-th-large fa-fw')
                ->setExtra('translation_domain', 'menu')
                ->setExtra('routes', [
                    ['pattern' => '/category/'],
                ])
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return $this
     */
    private function addStatus(ItemInterface $menu)
    {
        if ($this->isGranted('ROLE_CAN_EDIT_LOOKUP_VALUES')) {
            $menu
                ->addChild('menu.tutor_status', ['route' => 'status'])
                ->setAttribute('icon', 'fa fa-tag fa-fw')
                ->setExtra('translation_domain', 'menu')
                ->setExtra('routes', [
                    ['pattern' => '/status/'],
                ])
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return $this
     */
    private function addRegions(ItemInterface $menu)
    {
        if ($this->isGranted('ROLE_CAN_EDIT_LOOKUP_VALUES')) {
            $menu
                ->addChild('menu.regions', ['route' => 'region'])
                ->setAttribute('icon', 'fa fa-globe fa-fw')
                ->setExtra('translation_domain', 'menu')
                ->setExtra('routes', [
                    ['pattern' => '/region/'],
                ])
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return $this
     */
    private function addCountries(ItemInterface $menu)
    {
        if ($this->isGranted('ROLE_CAN_EDIT_LOOKUP_VALUES')) {
            $menu
                ->addChild('menu.countries', ['route' => 'country'])
                ->setAttribute('icon', 'fa fa-flag-o fa-fw')
                ->setExtra('translation_domain', 'menu')
                ->setExtra('routes', [
                    ['pattern' => '/country/'],
                ])
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return $this
     */
    private function addCurrencies(ItemInterface $menu)
    {
        if ($this->isGranted('ROLE_CAN_EDIT_LOOKUP_VALUES')) {
            $menu
                ->addChild('menu.currencies', ['route' => 'currency'])
                ->setAttribute('icon', 'fa fa-money fa-fw')
                ->setExtra('translation_domain', 'menu')
                ->setExtra('routes', [
                    ['pattern' => '/currency/'],
                ])
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return $this
     */
    private function addLanguages(ItemInterface $menu)
    {
        if ($this->isGranted('ROLE_CAN_EDIT_LOOKUP_VALUES')) {
            $menu
                ->addChild('menu.languages', ['route' => 'language'])
                ->setAttribute('icon', 'fa fa-language fa-fw')
                ->setExtra('translation_domain', 'menu')
                ->setExtra('routes', [
                    ['pattern' => '/language/'],
                ])
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return $this
     */
    private function addFileTypes(ItemInterface $menu)
    {
        if ($this->isGranted('ROLE_CAN_EDIT_LOOKUP_VALUES')) {
            $menu
                ->addChild('menu.file_type', ['route' => 'file_type'])
                ->setAttribute('icon', 'fa fa-files-o fa-fw')
                ->setExtra('translation_domain', 'menu')
                ->setExtra('routes', [
                    ['pattern' => '/file_type/'],
                ])

            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return $this
     */
    private function addCompetencyTypes(ItemInterface $menu)
    {
        if ($this->isGranted('ROLE_CAN_EDIT_LOOKUP_VALUES')) {
            $menu
                ->addChild('menu.competency_type', ['route' => 'competency_type'])
                ->setAttribute('icon', 'fa fa-th fa-fw')
                ->setExtra('translation_domain', 'menu')
                ->setExtra('routes', [
                    ['pattern' => '/competency_type/'],
                ])
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return $this
     */
    private function addCompetencyLevels(ItemInterface $menu)
    {
        if ($this->isGranted('ROLE_CAN_EDIT_LOOKUP_VALUES')) {
            $menu
                ->addChild('menu.competency_level', ['route' => 'competency_level'])
                ->setAttribute('icon', 'fa fa-signal fa-fw')
                ->setExtra('translation_domain', 'menu')
                ->setExtra('routes', [
                    ['pattern' => '/competency_level/'],
                ])
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return $this
     */
    private function addUsers(ItemInterface $menu)
    {
        if ($this->isGranted('ROLE_CAN_MANAGE_USERS')) {
            $menu
                ->addChild('menu.user_management', ['route' => 'user'])
                ->setAttribute('icon', 'fa fa-users fa-fw')
                ->setExtra('translation_domain', 'menu')
                ->setExtra('routes', [
                    ['pattern' => '/user/'],
                ])
            ;
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return $this
     */
    private function addProfile(ItemInterface $menu)
    {
        if ($this->isGranted('ROLE_CAN_VIEW_TUTOR')) {
            $menu
                ->addChild('menu.profile')
                ->setAttribute('icon', 'fa fa-mortar-board fa-fw')
                ->setExtra('translation_domain', 'menu')
                ->setExtra('routes', [
                    ['pattern' => '/profile/'],
                ])
            ;
        }

        return $this;
    }

    /**
     * @param $role
     *
     * @return bool
     */
    public function isGranted($role)
    {
        return (bool) $this->container->get('security.context')->isGranted($role);
    }
}
