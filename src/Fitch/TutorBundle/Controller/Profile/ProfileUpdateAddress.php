<?php

namespace Fitch\TutorBundle\Controller\Profile;

use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\AddressManagerInterface;
use Fitch\TutorBundle\Model\CountryManagerInterface;
use Fitch\TutorBundle\Model\Traits\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;

class ProfileUpdateAddress implements ProfileUpdateInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    public function update(Request $request, Tutor $tutor, $value)
    {
        $addressId = $request->request->get('addressPk');
        if ($addressId) {
            $address = $this->getAddressManager()->findById($addressId);
        } else {
            $address = $this->getAddressManager()->createEntity();
            $tutor->addAddress($address);
        }
        $address
            ->setType($value['type'])
            ->setStreetPrimary($value['streetPrimary'])
            ->setStreetSecondary($value['streetSecondary'])
            ->setCity($value['city'])
            ->setState($value['state'])
            ->setZip($value['zip'])
            ->setCountry($this->getCountryManager()->findById($value['country']))
        ;

        return $address;
    }

    /**
     * @return CountryManagerInterface
     */
    private function getCountryManager()
    {
        return $this->container->get('fitch.manager.country');
    }

    /**
     * @return AddressManagerInterface
     */
    private function getAddressManager()
    {
        return $this->container->get('fitch.manager.address');
    }
}
