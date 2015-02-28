<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\EmailRepository;
use Fitch\TutorBundle\Entity\Email;

class EmailManager extends BaseModelManager implements EmailManagerInterface
{
    /**
     * Create a new Email.
     *
     * Set its default values
     *
     * @return Email
     */
    public function createEmail()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeEmail($id)
    {
        $email = $this->findById($id);
        parent::removeEntity($email);
    }

    /**
     * @param Email $email
     */
    public function refreshEmail(Email $email)
    {
        parent::reloadEntity($email);
    }

    /**
     * @return EmailRepository
     */
    private function getRepo()
    {
        return $this->repo;
    }

    /**
     * Used  to identify logs generated by this class.
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.email';
    }
}
