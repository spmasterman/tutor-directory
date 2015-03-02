<?php

namespace Fitch\TutorBundle\Model\Profile;

use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\PhoneManagerInterface;
use Fitch\TutorBundle\Model\Traits\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProfileUpdatePhone.
 */
class ProfileUpdatePhone implements ProfileUpdateInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    public function update(Request $request, Tutor $tutor, $value)
    {
        $phoneId = $request->request->get('phonePk');
        if ($phoneId) {
            $phone = $this->getPhoneManager()->findById($phoneId);
        } else {
            $phone = $this->getPhoneManager()->createEntity();
            $tutor->addPhoneNumber($phone);
        }
        $phone
            ->setType($value['type'])
            ->setNumber($value['number'])
            ->setCountry($this->getCountryManager()->findById($value['country']))
            ->setPreferred($value['isPreferred'] == "true")
        ;

        return $phone;
    }

    /**
     * @return PhoneManagerInterface
     */
    private function getPhoneManager()
    {
        return $this->container->get('fitch.manager.phone');
    }

    /**
     * @return \Fitch\TutorBundle\Model\CountryManagerInterface
     */
    private function getCountryManager()
    {
        return $this->container->get('fitch.manager.country');
    }
}
