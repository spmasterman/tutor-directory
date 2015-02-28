<?php

namespace Fitch\CommonBundle\Model;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UserCallable.
 *
 * Wraps the token storage service and offers a single method getCurrentUser. Exists really because prior to 2.6
 * injecting the security.context in anything would cause a 'service dependency circle', injecting the whole
 * container in here, and just using ->get('security.context') didn't *change* the dependencies, but served to at least
 * stop the whole container being available in anything that wanted to see the current user - which would just encourage
 * dependency creep.
 *
 * The class is retained because it helps make the actual dependency a little more expressive.
 */
class UserCallable implements UserCallableInterface
{
    /** @var TokenStorageInterface  * */
    protected $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getCurrentUser()
    {
        return $this->tokenStorage->getToken()->getUser() ?: false;
    }
}
