<?php

namespace Fitch\TutorBundle\Model\Interfaces;

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
    public function saveEmail($email, $withFlush = true);

    /**
     * Create a new Email.
     *
     * Set its default values
     *
     * @return Email
     */
    public function createEmail();

    /**
     * @param int $id
     */
    public function removeEmail($id);

    /**
     * @param Email $email
     */
    public function refreshEmail(Email $email);
}
