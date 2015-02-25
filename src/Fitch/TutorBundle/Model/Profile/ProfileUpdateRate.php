<?php

namespace Fitch\TutorBundle\Model\Profile;

use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\RateManagerInterface;
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
            $rate = $this->getRateManager()->createRate();
            $tutor->addRate($rate);
        }
        $rate
            ->setName($value['name'])
            ->setAmount($value['amount'])
        ;

        return $rate;
    }

    /**
     * @return RateManagerInterface
     */
    private function getRateManager()
    {
        return $this->container->get('fitch.manager.rate');
    }
}
