<?php

namespace Fitch\EntityAttributeValueBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="attribute_definition")
 */
class Definition implements IdentityTraitInterface
{
    use IdentityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Schema",
     *      inversedBy="definitions"
     * )
     * @ORM\JoinColumn(name="schema_id", referencedColumnName="id")
     *
     * @var Schema
     */
    protected $schema;

    /**
     * @ORM\OneToMany(targetEntity="Option",
     *      mappedBy="definition",
     *      orphanRemoval=true,
     *      cascade={"persist", "remove"},
     *      fetch="EXTRA_LAZY"
     * )
     *
     * @var Option[]
     */
    protected $options;

    /**
     * @ORM\OneToMany(targetEntity="Attribute",
     *      mappedBy="definition",
     *      cascade={"remove"}
     * )
     *
     * @var Attribute[]
     */
    protected $attributes;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(name="default_value", type="string", length=255, nullable=true)
     *
     * @var string
     */
    protected $default;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $label;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @var string
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    protected $unit;

    /**
     * Allow definitions to be arbitrarily grouped.
     *
     * @ORM\Column(name="subset", type="string", length=255, nullable=true)
     *
     * @var string
     */
    protected $group;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $required = false;

    /**
     * @ORM\Column(name="sort_order", type="integer", nullable=true)
     *
     * @var string
     */
    protected $sortOrder;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->options = new ArrayCollection();
        $this->attributes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Definition
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
     * Set description.
     *
     * @param string $description
     *
     * @return Definition
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Definition
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set unit.
     *
     * @param string $unit
     *
     * @return Definition
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit.
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set required.
     *
     * @param boolean $required
     *
     * @return Definition
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get required.
     *
     * @return boolean
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @return string
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param string $sortOrder
     *
     * @return $this
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Set schema.
     *
     * @param Schema $schema
     *
     * @return $this
     */
    public function setSchema(Schema $schema = null)
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * Get schema.
     *
     * @return Schema
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * Add option.
     *
     * @param Option $option
     *
     * @return $this
     */
    public function addOption(Option $option)
    {
        $this->options[] = $option;
        $option->setDefinition($this);

        return $this;
    }

    /**
     * Remove option.
     *
     * @param Option $option
     */
    public function removeOption(Option $option)
    {
        $this->options->removeElement($option);
    }

    /**
     * Get options.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Add attributes.
     *
     * @param Attribute $attribute
     *
     * @return $this
     */
    public function addAttribute(Attribute $attribute)
    {
        $this->attributes[] = $attribute;

        return $this;
    }

    /**
     * Remove attribute.
     *
     * @param Attribute $attribute
     */
    public function removeAttribute(Attribute $attribute)
    {
        $this->attributes->removeElement($attribute);
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
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $group
     *
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param string $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }
}
