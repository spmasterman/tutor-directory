<?php

namespace Fitch\TutorBundle\Model\Profile;

use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\StatusManagerInterface;
use Fitch\TutorBundle\Model\Traits\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;

class ProfileUpdateStatus implements ProfileUpdateInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    public function update(Request $request, Tutor $tutor, $value)
    {
        $status = $this->getStatusManager()->findById($value);
        $tutor->setStatus($status);

        return;
    }

    /**
     * @return StatusManagerInterface
     */
    private function getStatusManager()
    {
        return $this->container->get('fitch.manager.status');
    }
}
