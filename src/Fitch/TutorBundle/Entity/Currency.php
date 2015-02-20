<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\NamedTrait;
use Fitch\CommonBundle\Entity\NamedTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Currency
 *
 * @ORM\Table(name="currency")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\CurrencyRepository")
 */
class Currency implements IdentityTraitInterface, TimestampableTraitInterface, NamedTraitInterface
{
    use IdentityTrait, TimestampableTrait, NamedTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    protected $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="iso_code_three", type="string", length=3)
     */
    protected $threeDigitCode;

    /**
     * @ORM\Column(name="preferred", type="boolean")
     *
     * @var boolean
     */
    protected $preferred;

    /**
     * @ORM\Column(name="active", type="boolean")
     *
     * @var boolean
     */
    protected $active = true;

    /**
     * @var float
     *
     * @ORM\Column(name="x_rate_gbp", type="decimal", precision=16, scale=8)
     */
    protected $toGBP = 1.00;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="x_rate_updated", type="datetime", nullable=true)
     */
    protected $rateUpdated;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getThreeDigitCode() . ' - ' . $this->getName();
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

    /**
     * @return float
     */
    public function getToGBP()
    {
        return $this->toGBP;
    }

    /**
     * @param float $toGBP
     * @return $this
     */
    public function setToGBP($toGBP)
    {
        $this->toGBP = $toGBP;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRateUpdated()
    {
        return $this->rateUpdated;
    }

    /**
     * @param \DateTime $rateUpdated
     * @return $this
     */
    public function setRateUpdated($rateUpdated)
    {
        $this->rateUpdated = $rateUpdated;
        return $this;
    }
}
