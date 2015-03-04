<?php

namespace Fitch\EntityAttributeValueBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityEntityTrait;
use Fitch\CommonBundle\Entity\IdentityEntityInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table("attribute_schema")
 */
class Schema implements IdentityEntityInterface
{
    use IdentityEntityTrait;

    /**
     * @ORM\Column(name="class_name", type="string", length=255)
     *
     * @var string
     */
    protected $className;

    /**
     * @Assert\Valid
     *
     * @ORM\OneToMany(targetEntity="Definition",
     *      mappedBy="schema",
     *      orphanRemoval=true,
     *      cascade={"persist", "remove"}
     * )
     * @ORM\OrderBy({"sortOrder" = "ASC"})
     *
     * @var Definition[]
     */
    protected $definitions;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->definitions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->className;
    }

    /**
     * Add definition.
     *
     * @param Definition $definition
     *
     * @return $this
     */
    public function addDefinition(Definition $definition)
    {
        $definition->setSchema($this);
        $this->definitions[] = $definition;

        return $this;
    }

    /**
     * Remove definition.
     *
     * @param Definition $definition
     */
    public function removeDefinition(Definition $definition)
    {
        $this->definitions->removeElement($definition);
    }

    /**
     * Get definitions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * Set className.
     *
     * @param string $className
     *
     * @return $this
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get className.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }
}
