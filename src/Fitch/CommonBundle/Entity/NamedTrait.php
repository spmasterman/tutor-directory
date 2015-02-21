<?php

namespace Fitch\CommonBundle\Entity;

trait NamedTrait
{
    // Don't declare the name property in here, as it needs to be declared with ORM annotations, in the 'host' class
    // (the ORM declarations will be different)

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
