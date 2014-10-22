<?php

namespace Fitch\FrontEndBundle\Menu;

use Fitch\BotBundle\Entity\Bot;
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
}
