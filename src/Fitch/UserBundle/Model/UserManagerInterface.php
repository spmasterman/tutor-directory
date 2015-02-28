<?php
/**
 * Created by PhpStorm.
 * User: smasterman
 * Date: 28/02/15
 * Time: 15:45.
 */

namespace Fitch\UserBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Exception\InappropriateActionException;
use Fitch\EntityAttributeValueBundle\Entity\Attribute;
use Fitch\UserBundle\Entity\User;

interface UserManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return User
     */
    public function findById($id);

    /**
     * @return User[]
     */
    public function findAll();

    /**
     * @param User $user
     * @param $name
     *
     * @throws EntityNotFoundException
     *
     * @return Attribute
     */
    public function findAttributeByName(User $user, $name);

    /**
     * @param $user
     * @param $name
     *
     * @return array
     */
    public function findAttributeGroupAsMap(User $user, $name);

    public function createWidgetControlDefinition($widgetName);

    /**
     * @param User $user
     *
     * @return array
     */
    public function getLogs(User $user);

    /**
     * @param User $user
     * @param bool $withFlush
     */
    public function saveEntity(User $user, $withFlush = true);

    /**
     * Create a new User.
     *
     * Set its default values
     *
     * @return User
     */
    public function createUser();

    /**
     * @param int $id
     *
     * @throws InappropriateActionException
     */
    public function removeUser($id);

    /**
     * @param User $user
     */
    public function reloadUser($user);
}
