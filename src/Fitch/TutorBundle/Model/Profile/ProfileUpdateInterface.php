<?php

namespace Fitch\TutorBundle\Model\Profile;

use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\TutorBundle\Entity\Tutor;
use Symfony\Component\HttpFoundation\Request;

interface ProfileUpdateInterface
{
    /**
     * @param Request $request
     * @param Tutor   $tutor
     * @param $value
     *
     * @return IdentityTraitInterface|null
     */
    public function update(Request $request, Tutor $tutor, $value);
}
