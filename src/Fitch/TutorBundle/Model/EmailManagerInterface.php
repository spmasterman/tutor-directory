<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Email;

interface EmailManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Email
     */
    public function findById($id);

    /**
     * @return Email[]
     */
    public function findAll();

    /**
     * @param Email $email
     * @param bool  $withFlush
     */
    public function saveEntity($email, $withFlush = true);

    /**
     * Create a new Email.
     *
     * Set its default values
     *
     * @return Email
     */
    public function createEntity();

    /**
     * @param Email $entity
     * @param bool $withFlush
     */
    public function removeEntity($entity, $withFlush = true);

    /**
     * @param Email $email
     */
    public function reloadEntity($email);
}
