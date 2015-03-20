<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\ActiveAndPreferredEntityInterface;
use Fitch\CommonBundle\Entity\ActiveAndPreferredEntityTrait;
use Fitch\CommonBundle\Entity\IdentityEntityInterface;
use Fitch\CommonBundle\Entity\IdentityEntityTrait;
use Fitch\CommonBundle\Entity\NamedEntityInterface;
use Fitch\CommonBundle\Entity\NamedEntityTrait;
use Fitch\CommonBundle\Entity\TimestampableEntityInterface;
use Fitch\CommonBundle\Entity\TimestampableEntityTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Currency.
 *
 * @ORM\Table(name="currency")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\CurrencyRepository")
 * @UniqueEntity("name")
 */
class Currency implements
    IdentityEntityInterface,
    TimestampableEntityInterface,
    NamedEntityInterface,
    ActiveAndPreferredEntityInterface
{
    use IdentityEntityTrait, TimestampableEntityTrait, NamedEntityTrait, ActiveAndPreferredEntityTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, unique=true)
     * @Assert\NotBlank())
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_code_three", type="string", length=3)
     */
    protected $threeDigitCode;

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
        return $this->getThreeDigitCode().' - '.$this->getName();
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
     *
     * @return $this
     */
    public function setThreeDigitCode($threeDigitCode)
    {
        $this->threeDigitCode = $threeDigitCode;

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
     *
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
     *
     * @return $this
     */
    public function setRateUpdated($rateUpdated)
    {
        $this->rateUpdated = $rateUpdated;

        return $this;
    }
}
