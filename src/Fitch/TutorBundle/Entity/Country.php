<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\CountryRepository")
 */
class Country implements IdentityTraitInterface, TimestampableTraitInterface
{
    use IdentityTrait, TimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_code_two", type="string", length=2)
     */
    protected $twoDigitCode;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_code_three", type="string", length=3)
     */
    protected $threeDigitCode;

    /**
     * @var string
     *
     * @ORM\Column(name="dialing_prefix", type="string", length=12)
     */
    protected $dialingCode;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="OperatingRegion"
     * )
     * @ORM\JoinColumn(name="default_region_id", referencedColumnName="id", onDelete="SET NULL")
     * @var OperatingRegion
     */
    protected $defaultRegion;

    /**
     * @ORM\Column(name="preferred", type="boolean")
     *
     * @var boolean
     */
    protected $preferred = false;

    /**
     * @ORM\Column(name="active", type="boolean")
     *
     * @var boolean
     */
    protected $active = true;

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s (%s) %s', $this->getName(), $this->getThreeDigitCode(), $this->getDialingCode());
    }

    /**
     * @return OperatingRegion
     */
    public function getDefaultRegion()
    {
        return $this->defaultRegion;
    }

    /**
     * @param OperatingRegion $defaultRegion
     * @return $this
     */
    public function setDefaultRegion($defaultRegion)
    {
        $this->defaultRegion = $defaultRegion;
        return $this;
    }

    /**
     * @return string
     */
    public function getDialingCode()
    {
        return $this->dialingCode;
    }

    /**
     * @param string $dialingCode
     * @return $this
     */
    public function setDialingCode($dialingCode)
    {
        $this->dialingCode = $dialingCode;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPreferred()
    {
        return $this->preferred;
    }

    /**
     * @param boolean $preferred
     * @return $this
     */
    public function setPreferred($preferred)
    {
        $this->preferred = $preferred;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getThreeDigitCode()
    {
        return $this->threeDigitCode;
    }

    /**
     * @param string $threeDigitCode
     * @return $this
     */
    public function setThreeDigitCode($threeDigitCode)
    {
        $this->threeDigitCode = $threeDigitCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getTwoDigitCode()
    {
        return $this->twoDigitCode;
    }

    /**
     * @param string $twoDigitCode
     * @return $this
     */
    public function setTwoDigitCode($twoDigitCode)
    {
        $this->twoDigitCode = $twoDigitCode;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }
}
