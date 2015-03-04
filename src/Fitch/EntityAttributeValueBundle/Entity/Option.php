<?php

namespace Fitch\EntityAttributeValueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityEntityTrait;
use Fitch\CommonBundle\Entity\IdentityEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="attribute_option")
 */
class Option implements IdentityEntityInterface
{
    use IdentityEntityTrait;
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Definition",
     *      inversedBy="options"
     * )
     * @ORM\JoinColumn(name="definition_id", referencedColumnName="id")
     *
     * @var Definition
     */
    protected $definition;

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Option
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
     * Set definition.
     *
     * @param Definition $definition
     *
     * @return $this
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
