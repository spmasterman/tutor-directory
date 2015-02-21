<?php

namespace Fitch\CommonBundle\Entity;

interface ActiveAndPreferredTraitInterface
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
