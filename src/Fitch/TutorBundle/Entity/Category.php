<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\DefaultTrait;
use Fitch\CommonBundle\Entity\DefaultTraitInterface;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\NamedTrait;
use Fitch\CommonBundle\Entity\NamedTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
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
    IdentityTraitInterface,
    TimestampableTraitInterface,
    NamedTraitInterface,
    DefaultTraitInterface
{
    use IdentityTrait, TimestampableTrait, NamedTrait, DefaultTrait;

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
