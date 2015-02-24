<?php

namespace Fitch\CommonBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;

trait AuthorisedClientTrait
{
    /**
     * @param $userName
     *
     * @return Client
     */
    protected function createAuthorizedClient($userName)
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $session = $container->get('session');
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $container->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        $user = $userManager->findUserBy(array('username' => $userName));
        $loginManager->loginUser($firewallName, $user);

        // save the login token into the session and put it in a cookie
        $container->get('session')->set(
            '_security_'.$firewallName,
            serialize($container->get('security.context')->getToken())
        );
        $container->get('session')->save();
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }

    protected function checkAccess($method, $path, $users)
    {
        foreach ($users as $userName => $expectedHTTPResponseCode) {
            if ($userName) {
                $client = $this->createAuthorizedClient($userName);
            } else {
                $client = static::createClient();
            }
            $client->request($method, $path);
            $this->assertEquals(
                $expectedHTTPResponseCode,
                $client->getResponse()->getStatusCode(),
                "Unexpected HTTP status code for $method $path"
            );
        }
    }
}
