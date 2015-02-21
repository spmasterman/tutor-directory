<?php

namespace Fitch\EntityAttributeValueBundle\Entity;

interface AttributedEntityInterface
{
    /**
     * Add attributes.
     *
     * @param \Fitch\EntityAttributeValueBundle\Entity\Attribute $attribute
     *
     * @return $this
     */
    public function addAttribute(Attribute $attribute);

    /**
     * Remove attributes.
     *
     * @param \Fitch\EntityAttributeValueBundle\Entity\Attribute $attribute
     */
    public function removeAttribute(Attribute $attribute);

    /**
     * Get attributes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttributes();
}
