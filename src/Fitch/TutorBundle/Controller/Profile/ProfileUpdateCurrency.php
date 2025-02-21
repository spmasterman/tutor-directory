<?php

namespace Fitch\TutorBundle\Controller\Profile;

use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\CurrencyManagerInterface;
use Fitch\TutorBundle\Model\Traits\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;

class ProfileUpdateCurrency implements ProfileUpdateInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    public function update(Request $request, Tutor $tutor, $value)
    {
        $currency = $this->getCurrencyManager()->findById($value);
        $tutor->setCurrency($currency);

        return;
    }

    /**
     * @return CurrencyManagerInterface
     */
    private function getCurrencyManager()
    {
        return $this->container->get('fitch.manager.currency');
    }
}
