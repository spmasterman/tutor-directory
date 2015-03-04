<?php

namespace Fitch\CommonBundle\Entity;

/**
 * Interface NamedEntityInterface.
 */
interface NamedEntityInterface
{
    /**
     * Set name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * Get name.
     *
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function __toString();
}
