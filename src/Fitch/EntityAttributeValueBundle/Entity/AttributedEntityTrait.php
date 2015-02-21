<?php

namespace Fitch\EntityAttributeValueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait AttributedEntityTrait
{
    /**
     * @ORM\ManyToMany(targetEntity="Fitch\EntityAttributeValueBundle\Entity\Attribute",
     *      fetch="EAGER",
     *      indexBy="definition",
     *      cascade={"persist", "remove"}
     *  )
     *
     * @var \Fitch\EntityAttributeValueBundle\Entity\Attribute[]
     */
    protected $attributes;

    /**
     * Add attributes.
     *
     * @param \Fitch\EntityAttributeValueBundle\Entity\Attribute $attribute
     *
     * @return $this
     */
    public function addAttribute(Attribute $attribute)
    {
        $this->attributes[] = $attribute;

        return $this;
    }

    /**
     * Remove attributes.
     *
     * @param \Fitch\EntityAttributeValueBundle\Entity\Attribute $attribute
     */
    public function removeAttribute(Attribute $attribute)
    {
        $this->getAttributes()->removeElement($attribute);
    }

    /**
     * Get attributes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param $name
     *
     * @return Attribute|null
     */
    public function getAttributeNamed($name)
    {
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getDefinition() == $name) {
                return $attribute;
            }
        }

        return;
    }
}
