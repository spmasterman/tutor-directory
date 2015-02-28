<?php

namespace Fitch\TutorBundle\Model\Profile;

use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\Traits\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;

class ProfileUpdateRate implements ProfileUpdateInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    public function update(Request $request, Tutor $tutor, $value)
    {
        $rateId = $request->request->get('ratePk');
        if ($rateId) {
            $rate = $this->getRateManager()->findById($rateId);
        } else {
            $rate = $this->getRateManager()->createEntity();
            $tutor->addRate($rate);
        }
        $rate
            ->setName($value['name'])
            ->setAmount($value['amount'])
        ;

        return $rate;
    }

    /**
     * @return \Fitch\TutorBundle\Model\RateManagerInterface
     */
    private function getRateManager()
    {
        return $this->container->get('fitch.manager.rate');
    }
}
