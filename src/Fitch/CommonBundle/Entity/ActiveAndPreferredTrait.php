<?php

namespace Fitch\CommonBundle\Entity;

trait ActiveAndPreferredTrait
{
    /**
     * @ORM\Column(name="preferred", type="boolean")
     *
     * @var boolean
     */
    protected $preferred = false;

    /**
     * @ORM\Column(name="active", type="boolean")
     *
     * @var boolean
     */
    protected $active = true;

    /**
     * @return boolean
     */
    public function isPreferred()
    {
        return $this->preferred;
    }

    /**
     * @param boolean $preferred
     * @return $this
     */
    public function setPreferred($preferred)
    {
        $this->preferred = $preferred;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }
}
