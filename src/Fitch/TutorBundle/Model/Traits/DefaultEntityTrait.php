<?php

namespace Fitch\TutorBundle\Model\Traits;

trait DefaultEntityTrait
{
    public function findDefaultEntity()
    {
        return $this->getRepo()->findOneBy(['default' => true]);
    }
}
