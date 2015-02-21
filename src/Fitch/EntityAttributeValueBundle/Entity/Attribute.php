<?php

namespace Fitch\EntityAttributeValueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;

/**
 * @ORM\Entity()
 * @ORM\Table("attribute")
 */
class Attribute implements IdentityTraitInterface
{
    use IdentityTrait;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    protected $value;

    /**
     * @ORM\ManyToOne(targetEntity="Definition",
     *      inversedBy="attributes",
     *      cascade={"persist", "remove"}
     * )
     * @ORM\JoinColumn(name="definition_id", referencedColumnName="id")
     *
     * @var Definition
     */
    protected $definition;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getDefinition()->getName();
    }

    /**
     * Set value.
     *
     * @param string $value
     *
     * @return Attribute
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set definition.
     *
     * @param Definition $definition
     *
     * @return Attribute
     */
    public function setDefinition(Definition $definition = null)
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * Get definition.
     *
     * @return Definition
     */
    public function getDefinition()
    {
        return $this->definition;
    }
}
