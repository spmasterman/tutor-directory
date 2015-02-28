<?php

namespace Fitch\CommonBundle\Model;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UserCallable.
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
