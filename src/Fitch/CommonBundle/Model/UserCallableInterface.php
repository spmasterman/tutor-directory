<?php

namespace Fitch\CommonBundle\Model;

/**
 * Interface UserCallableInterface.
 */
interface UserCallableInterface
{
    /**
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getCurrentUser();
}
