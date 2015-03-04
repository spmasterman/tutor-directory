<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\DefaultableEntityTrait;
use Fitch\CommonBundle\Entity\DefaultableEntityInterface;
use Fitch\CommonBundle\Entity\IdentityEntityTrait;
use Fitch\CommonBundle\Entity\IdentityEntityInterface;
use Fitch\CommonBundle\Entity\NamedEntityTrait;
use Fitch\CommonBundle\Entity\NamedEntityInterface;
use Fitch\CommonBundle\Entity\TimestampableEntityTrait;
use Fitch\CommonBundle\Entity\TimestampableEntityInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category.
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\CategoryRepository")
 * @UniqueEntity("name")
 */
class Category implements
    IdentityEntityInterface,
    TimestampableEntityInterface,
    NamedEntityInterface,
    DefaultableEntityInterface
{
    use IdentityEntityTrait, TimestampableEntityTrait, NamedEntityTrait, DefaultableEntityTrait;

    /**
     * @var ArrayCollection
     *
     * INVERSE SIDE
     * @ORM\OneToMany(targetEntity="CompetencyType",
     *      mappedBy="category",
     *      indexBy="id",
     *      cascade={"persist", "remove"}
     * )
     */
    protected $competencyTypes;

    /**
     * @ORM\ManyToOne(targetEntity="BusinessArea", inversedBy="categories")
     * @ORM\JoinColumn(name="business_area_id", referencedColumnName="id")
     *
     * @var BusinessArea
     */
    protected $businessArea;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128, unique=true)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @return string
     */
    public function __toString()
    {
        return ($this->businessArea->isPrependToCategoryName()
                    ? $this->businessArea->__toString().': '
                    : '')
        .$this->getName();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->competencyTypes = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getCompetencyTypes()
    {
        return $this->competencyTypes;
    }

    /**
     * @param mixed $competencyTypes
     *
     * @return $this
     */
    public function setCompetencyTypes($competencyTypes)
    {
        $this->competencyTypes = $competencyTypes;

        return $this;
    }

    /**
     * @return BusinessArea
     */
    public function getBusinessArea()
    {
        return $this->businessArea;
    }

    /**
     * @param BusinessArea $businessArea
     *
     * @return $this
     */
    public function setBusinessArea($businessArea)
    {
        $this->businessArea = $businessArea;

        return $this;
    }
}
