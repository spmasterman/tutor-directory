<?php

namespace Fitch\TutorBundle\Controller\Profile;

use Fitch\CommonBundle\Entity\IdentityEntityInterface;
use Fitch\TutorBundle\Entity\Tutor;
use Symfony\Component\HttpFoundation\Request;

interface ProfileUpdateInterface
{
    /**
     * @param Request $request
     * @param Tutor   $tutor
     * @param $value
     *
     * @return IdentityEntityInterface|null
     */
    public function update(Request $request, Tutor $tutor, $value);
}
