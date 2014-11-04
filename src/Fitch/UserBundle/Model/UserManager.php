<?php

namespace Fitch\UserBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Exception\InappropriateActionException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\UserBundle\Entity\Repository\UserRepository;
use Fitch\UserBundle\Entity\User;

class UserManager extends BaseModelManager
{
    /**
     * @param $id
     * @throws EntityNotFoundException
     * @return User
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return User[]
     */
    public function findAll()
    {
        return parent::findAll();
    }


    /**
     * @param User $user
     * @param bool $withFlush
     */
    public function saveUser(User $user, $withFlush = true)
    {
        parent::saveEntity($user, $withFlush);
    }

    /**
     * Create a new User
     *
     * Set its default values
     *
     * @throws InappropriateActionException
     */
    public function createUser()
    {
        throw new InappropriateActionException("Please don't create users manually. Use the command line tools");
    }

    /**
     * @param int $id
     * @throws InappropriateActionException
     */
    public function removeUser($id)
    {
        throw new InappropriateActionException("Please don't delete users manually. Use the command line tools");
    }

    /**
     * @param User $user
     */
    public function refreshUser(User $user)
    {
        parent::reloadEntity($user);
    }

    /**
     * @return UserRepository
     */
    private function getRepo()
    {
        return $this->repo;
    }

    /**
     * Used  to identify logs generated by this class
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.user';
    }
}
