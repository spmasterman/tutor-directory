<?php

namespace Fitch\TutorBundle\Model\Profile;

use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\RateManagerInterface;
use Fitch\TutorBundle\Model\TutorTypeManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProfileUpdateTutorType implements ProfileUpdateInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    public function update(Request $request, Tutor $tutor, $value)
    {
        $tutorType = $this->getTutorTypeManager()->findById($value);
        $tutor->setTutorType($tutorType);

        return;
    }

    /**
     * @return TutorTypeManagerInterface
     */
    private function getTutorTypeManager()
    {
        return $this->container->get('fitch.manager.tutor_type');
    }
}
