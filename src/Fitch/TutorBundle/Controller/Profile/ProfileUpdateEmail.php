<?php

namespace Fitch\TutorBundle\Controller\Profile;

use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\EmailManagerInterface;
use Fitch\TutorBundle\Model\Traits\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;

class ProfileUpdateEmail implements ProfileUpdateInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    public function update(Request $request, Tutor $tutor, $value)
    {
        $emailId = $request->request->get('emailPk');
        if ($emailId) {
            $email = $this->getEmailManager()->findById($emailId);
        } else {
            $email = $this->getEmailManager()->createEntity();
            $tutor->addEmailAddress($email);
        }
        $email
            ->setType($value['type'])
            ->setAddress($value['address'])
        ;

        return $email;
    }

    /**
     * @return EmailManagerInterface
     */
    private function getEmailManager()
    {
        return $this->container->get('fitch.manager.email');
    }
}
