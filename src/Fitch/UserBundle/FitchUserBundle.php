<?php

namespace Fitch\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FitchUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
