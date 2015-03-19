<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\ArrayLoadableEntityInterface;
use Fitch\CommonBundle\Entity\ArrayLoadableEntityTrait;
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
 * BusinessArea.
 *
 * @ORM\Table(name="business_area")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\BusinessAreaRepository")
 * @UniqueEntity("name")
 */
class BusinessArea implements
    IdentityEntityInterface,
    TimestampableEntityInterface,
    NamedEntityInterface,
    DefaultableEntityInterface,
    ArrayLoadableEntityInterface
{
    use IdentityEntityTrait,
        TimestampableEntityTrait,
        NamedEntityTrait,
        DefaultableEntityTrait,
        ArrayLoadableEntityTrait
        ;

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
     * @ORM\Column(name="name", type="string", length=128, unique=true)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=4, nullable=true)
     */
    protected $code;

    /**
     * @var boolean
     *
     * @ORM\Column(name="prepend", type="boolean")
     */
    protected $prependToCategoryName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="show_as_code", type="boolean")
     */
    protected $displayAsCode;

    /**
     *
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->isDisplayAsCode()) {
            return $this->getCode() ?: $this->getName();
        } else {
            return ($this->getCode() ? "({$this->getCode()}) " : '').$this->getName();
        }
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

    /**
     * @return boolean
     */
    public function isPrependToCategoryName()
    {
        return $this->prependToCategoryName;
    }

    /**
     * @param boolean $prependToCategoryName
     *
     * @return $this
     */
    public function setPrependToCategoryName($prependToCategoryName)
    {
        $this->prependToCategoryName = $prependToCategoryName;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isDisplayAsCode()
    {
        return $this->displayAsCode;
    }

    /**
     * @param boolean $displayAsCode
     *
     * @return $this
     */
    public function setDisplayAsCode($displayAsCode)
    {
        $this->displayAsCode = $displayAsCode;

        return $this;
    }
}
