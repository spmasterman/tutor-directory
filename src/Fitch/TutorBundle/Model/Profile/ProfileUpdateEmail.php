<?php

namespace Fitch\TutorBundle\Model\Profile;

use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\Interfaces\EmailManagerInterface;
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
            $email = $this->getEmailManager()->createEmail();
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
