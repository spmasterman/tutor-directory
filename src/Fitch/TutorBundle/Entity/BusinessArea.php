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

/**
 * BusinessArea.
 *
 * @ORM\Table(name="business_area")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\BusinessAreaRepository")
 */
class BusinessArea implements
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
     * @ORM\OneToMany(targetEntity="Category",
     *      mappedBy="businessArea",
     *      indexBy="id",
     *      cascade={"persist", "remove"}
     * )
     */
    protected $categories;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=4, nullable=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128)
     */
    protected $name;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     *
     * @return $this
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
}
