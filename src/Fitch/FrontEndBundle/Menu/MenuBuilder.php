<?php

namespace Fitch\FrontEndBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class MenuBuilder extends ContainerAware
{
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
        ;

        return $menu;
    }


    public function breadcrumbMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory
            ->createItem('root', ['route' => 'home'])
            ->setAttribute('class', 'breadcrumb')
        ;

        $homeNode = $menu
            ->addChild('Home', ['route' => 'home'])
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
        ;

        return $menu;
    }

    public function userMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root')
                        ->setChildrenAttribute('class', 'dropdown-menu')
                        ->setChildrenAttribute('role', 'menu');

        $menu->addChild('Profile', array('route' => 'fos_user_profile_show'))
                ->setAttribute('icon', 'fa fa-user fa-fw')
                ->getParent()
             ->addChild('Settings', array('route' => 'home'))
                ->setAttribute('icon', 'fa fa-cog fa-fw')
                ->getParent()
             ->addChild('Logout', array('route' => 'fos_user_security_logout'))
                ->setAttribute('icon', 'fa fa-power-off fa-fw')
                ->getParent();
        return $menu;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return MenuBuilder
     */
    private function addTutorTypes(ItemInterface $menu)
    {
        $menu
            ->addChild('Tutor Types', array('route' => 'tutor_type'))
            ->setAttribute('icon', 'fa fa-tasks fa-fw')
        ;

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return MenuBuilder
     */
    private function addStatus(ItemInterface $menu)
    {
        $menu
            ->addChild('Statuses', array('route' => 'status'))
            ->setAttribute('icon', 'fa fa-tag fa-fw')
        ;

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return MenuBuilder
     */
    private function addRegions(ItemInterface $menu)
    {
        $menu
            ->addChild('Regions', array('route' => 'region'))
            ->setAttribute('icon', 'fa fa-globe fa-fw')
        ;

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return MenuBuilder
     */
    private function addCountries(ItemInterface $menu)
    {
        $menu
            ->addChild('Countries', array('route' => 'country'))
            ->setAttribute('icon', 'fa fa-flag-o fa-fw')
        ;

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return MenuBuilder
     */
    private function addCurrencies(ItemInterface $menu)
    {
        $menu
            ->addChild('Currencies', array('route' => 'currency'))
            ->setAttribute('icon', 'fa fa-money fa-fw')
        ;

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return MenuBuilder
     */
    private function addFileTypes(ItemInterface $menu)
    {
        $menu
            ->addChild('File Types', array('route' => 'file_type'))
            ->setAttribute('icon', 'fa fa-files-o fa-fw')
        ;

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return MenuBuilder
     */
    private function addCompetencyTypes(ItemInterface $menu)
    {
        $menu
            ->addChild('Competency Types', array('route' => 'competency_type'))
            ->setAttribute('icon', 'fa fa-bullseye fa-fw')
        ;

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return MenuBuilder
     */
    private function addCompetencyLevels(ItemInterface $menu)
    {
        $menu
            ->addChild('Competency Levels', array('route' => 'competency_level'))
            ->setAttribute('icon', 'fa fa-signal fa-fw')
        ;

        return $this;
    }
}

