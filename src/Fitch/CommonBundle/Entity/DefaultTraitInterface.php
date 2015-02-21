<?php

namespace Fitch\CommonBundle\Entity;

interface DefaultTraitInterface
{
    /**
     * @return boolean
     */
    public function isDefault();

    /**
     * @param boolean $default
     *
     * @return $this
     */
    public function setDefault($default);
}
