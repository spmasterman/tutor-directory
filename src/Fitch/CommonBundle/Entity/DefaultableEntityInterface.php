<?php

namespace Fitch\CommonBundle\Entity;

/**
 * Interface DefaultableEntityInterface.
 */
interface DefaultableEntityInterface
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
