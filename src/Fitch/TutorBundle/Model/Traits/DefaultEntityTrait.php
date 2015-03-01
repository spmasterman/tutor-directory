<?php

namespace Fitch\TutorBundle\Model\Traits;

trait DefaultEntityTrait
{
    /**
     * @return object
     */
    public function findDefaultEntity()
    {
        return $this->getRepo()->findOneBy(['default' => true]);
    }
}