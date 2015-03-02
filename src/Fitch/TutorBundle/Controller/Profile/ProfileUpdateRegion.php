<?php

namespace Fitch\TutorBundle\Controller\Profile;

use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\OperatingRegionManagerInterface;
use Fitch\TutorBundle\Model\Traits\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;

class ProfileUpdateRegion implements ProfileUpdateInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    public function update(Request $request, Tutor $tutor, $value)
    {
        $region = $this->getOperatingRegionManager()->findById($value);
        $tutor->setRegion($region);

        return;
    }

    /**
     * @return OperatingRegionManagerInterface
     */
    private function getOperatingRegionManager()
    {
        return $this->container->get('fitch.manager.operating_region');
    }
}
