<?php

namespace Fitch\CommonBundle\Entity;

/**
 * Interface ActiveAndPreferredEntityInterface.
 */
interface ActiveAndPreferredEntityInterface
{
    /**
     * @return boolean
     */
    public function isPreferred();

    /**
     * @param boolean $preferred
     *
     * @return $this
     */
    public function setPreferred($preferred);

    /**
     * @return boolean
     */
    public function isActive();

    /**
     * @param boolean $active
     *
     * @return $this
     */
    public function setActive($active);
}
