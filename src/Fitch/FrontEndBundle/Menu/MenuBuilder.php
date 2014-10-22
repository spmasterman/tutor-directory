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
        $bots = $this->container->get('doctrine')->getManager()->getRepository('FitchBotBundle:Bot')->findAll();

        $menu = $factory
            ->createItem('root', ['route' => 'dashboard'])
            ->setChildrenAttribute('class', 'main-menu')
        ;

        $this
            ->addFrontEnd($menu)
            ->addBots($menu, $bots)
            ->addScheduledTasks($menu)
            ->addConversationState($menu)
            ->addWordList($menu)
            ->addContent($menu)
            ->addInbox($menu)
            ->addNotifications($menu)
        ;

        return $menu;
    }

    public function breadcrumbMenu(FactoryInterface $factory, array $options)
    {
        $bots = $this->container->get('doctrine')->getManager()->getRepository('FitchBotBundle:Bot')->findAll();

        $menu = $factory
            ->createItem('root', ['route' => 'dashboard'])
            ->setAttribute('class', 'breadcrumb')

        ;

        $homeNode = $menu
            ->addChild('Home', ['route' => 'home'])
            ->setAttribute('icon', 'fa fa-home fa-fw');

        $this
            ->addFrontEnd($homeNode)
            ->addBots($homeNode, $bots)
            ->addScheduledTasks($homeNode)
            ->addConversationState($homeNode)
            ->addWordList($homeNode)
            ->addInbox($homeNode)
            ->addNotifications($homeNode)
        ;

        return $menu;
    }

    /**
     * @param ItemInterface $menu
     * @return $this
     */
    private function addFrontEnd(ItemInterface $menu)
    {
        $menu
            ->addChild('FrontEnd', ['route' => 'dashboard'])
            ->setAttribute('icon', 'fa fa-dashboard fa-fw')
        ;

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @param array [Bot] $bots
     * @return $this
     */
    private function addBots(ItemInterface $menu, $bots)
    {
        $node = $menu
            ->addChild('Twitter Bots', ['uri' => '#'])
            ->setChildrenAttribute('class', 'sub-menu')
            ->setAttribute('icon', 'fa fa-twitter fa-fw')
            ->setLinkAttribute('class', 'js-sub-menu-toggle')
            ->addChild('Show all', [
                'route' => 'bot'
            ])
            ->getParent();

        foreach ($bots as $bot) {
            /** @var Bot $bot */
            $node
                ->addChild($bot->getName(), [
                    'route' => 'bot_manage',
                    'routeParameters' => ['id' => $bot->getId()]
                ])
                ->setChildrenAttribute('class', 'sub-menu')
                ->addChild('Show', [
                        'route' => 'bot_show',
                        'routeParameters' => ['id' => $bot->getId()]
                    ])
                ->getParent()
                ->addChild('Edit Bot', [
                        'route' => 'bot_edit',
                        'routeParameters' => ['id' => $bot->getId()]
                    ])
                ->setAttribute('icon', 'fa fa-pencil fa-fw')
                ->getParent()
                ->addChild('Endpoints', [
                    'route' => 'bot_queue',
                    'routeParameters' => ['id' => $bot->getId()]
                ])
                ->setAttribute('icon', 'fa fa-tencent-weibo fa-rotate-270 fa-fw')
                ->getParent()
                ->addChild('Followers', [
                    'route' => 'followers',
                    'routeParameters' => ['botId' => $bot->getId()]
                ])
                ->setAttribute('icon', 'fa fa-group fa-fw')
                ->getParent()
                ->addChild('Mentions', [
                    'route' => 'incoming_messages',
                    'routeParameters' => ['botId' => $bot->getId()]
                ])
                ->setAttribute('icon', 'fa fa-comments fa-fw')
                ->getParent()
                ->addChild('Recipes', [
                    'route' => 'recipes',
                    'routeParameters' => ['botId' => $bot->getId()]
                ])
                ->setAttribute('icon', 'fa fa-spoon fa-fw')
                ->getParent()

            ;

        }
        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return $this
     */
    private function addScheduledTasks(ItemInterface $menu)
    {
        $menu
            ->addChild('Scheduled Tasks', array('route' => 'cron'))
            ->setAttribute('icon', 'fa fa-clock-o fa-fw')
        ;

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return $this
     */
    private function addConversationState(ItemInterface $menu)
    {
        $menu
            ->addChild('Conversation States', array('route' => 'constate'))
            ->setAttribute('icon', 'fa fa-microphone fa-fw')
            ->getParent()
        ;

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return $this
     */
    private function addWordList(ItemInterface $menu)
    {
        $menu
            ->addChild('Word Lists', array('route' => 'wordlist'))
            ->setAttribute('icon', 'fa fa-list-alt fa-fw')
            ->getParent()
        ;

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return $this
     */
    private function addContent(ItemInterface $menu)
    {
        $node = $menu
            ->addChild('Content', ['uri' => '#'])
            ->setAttribute('icon', 'fa fa-file-o fa-fw')
            ->setChildrenAttribute('class', 'sub-menu')
            ->setLinkAttribute('class', 'js-sub-menu-toggle')
        ;

        $node
            ->addChild('Import', ['route' => 'content_import'])
            ->setAttribute('icon', 'fa fa-cloud-download fa-fw')
            ->getParent()
            ->addChild('Questions', ['uri' => '#'])
            ->setAttribute('icon', 'fa fa-check-square-o fa-fw')
            ->getParent()
            ->addChild('Video', ['uri' => '#'])
            ->setAttribute('icon', 'fa fa-file-movie-o fa-fw')
            ->getParent()
            ->addChild('Audio', ['uri' => '#'])
            ->setAttribute('icon', 'fa fa-file-audio-o fa-fw')
            ->getParent()
        ;

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return $this
     */
    private function addInbox(ItemInterface $menu)
    {
        $node = $menu
            ->addChild('Messages', ['uri' => '#'])
            ->setChildrenAttribute('class', 'sub-menu')
            ->setAttribute('icon', 'fa fa-envelope fa-fw')
            ->setLinkAttribute('class', 'js-sub-menu-toggle')
        ;

        $node
            ->addChild('Inbox', [
                'route' => 'messages_folder',
                'routeParameters' => [
                    'folder' => 'inbox',
                    'offset' => 0,
                    'count' => 10,
                ]
            ])
            ->setAttribute('icon', 'fa fa-inbox fa-fw')
            ->getParent()
            ->addChild('Starred', [
                    'route' => 'messages_folder',
                    'routeParameters' => [
                        'folder' => 'starred',
                        'offset' => 0,
                        'count' => 10,
                    ]
                ])
            ->setAttribute('icon', 'fa fa-star fa-fw')
            ->getParent()
            ->addChild('Outbox', [
                    'route' => 'messages_folder',
                    'routeParameters' => [
                        'folder' => 'outbox',
                        'offset' => 0,
                        'count' => 10,
                    ]
                ])
            ->setAttribute('icon', 'fa fa-external-link fa-fw')
            ->getParent()
            ->addChild('Sent', [
                    'route' => 'messages_folder',
                    'routeParameters' => [
                        'folder' => 'sent',
                        'offset' => 0,
                        'count' => 10,
                    ]
                ])
            ->setAttribute('icon', 'fa fa-send fa-fw')
            ->getParent()
            ->addChild('Spam', [
                    'route' => 'messages_folder',
                    'routeParameters' => [
                        'folder' => 'spam',
                        'offset' => 0,
                        'count' => 10,
                    ]
                ])
            ->setAttribute('icon', 'fa fa-warning fa-fw')
            ->getParent()
            ->addChild('Trash', [
                    'route' => 'messages_folder',
                    'routeParameters' => [
                        'folder' => 'trash',
                        'offset' => 0,
                        'count' => 10,
                    ]
                ])
            ->setAttribute('icon', 'fa fa-trash fa-fw')
            ->getParent()
            ->addChild('Archive', [
                    'route' => 'messages_folder',
                    'routeParameters' => [
                        'folder' => 'archive',
                        'offset' => 0,
                        'count' => 10,
                    ]
                ])
            ->setAttribute('icon', 'fa fa-archive fa-fw')
            ->getParent()
        ;

        return $this;
    }

    /**
     * @param ItemInterface $menu
     * @return $this
     */
    private function addNotifications(ItemInterface $menu)
    {
        $menu
            ->addChild('Notifications', [
                'route' => 'notification',
                'routeParameters' => [
                    'offset' => 0,
                    'count' => 10,
                    'ack' =>'filtered'
                ]
            ])
            ->setAttribute('icon', 'fa fa-bell fa-fw')
            ->getParent()
        ;

        return $this;
    }

    public function userMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root')
                        ->setChildrenAttribute('class', 'dropdown-menu')
                        ->setChildrenAttribute('role', 'menu');

        $menu->addChild('Profile', array('route' => 'fos_user_profile_show'))
                ->setAttribute('icon', 'fa fa-user fa-fw')
                ->getParent()
             ->addChild('Settings', array('route' => 'dashboard'))
                ->setAttribute('icon', 'fa fa-cog fa-fw')
                ->getParent()
             ->addChild('Logout', array('route' => 'fos_user_security_logout'))
                ->setAttribute('icon', 'fa fa-power-off fa-fw')
                ->getParent();
        return $menu;
    }
}
