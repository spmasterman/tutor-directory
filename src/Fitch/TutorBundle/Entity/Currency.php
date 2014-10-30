<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Currency
 *
 * @ORM\Table(name="currency")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\CurrencyRepository")
 */
class Currency implements IdentityTraitInterface, TimestampableTraitInterface
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
     * @return string
     */
    public function __toString()
    {
        return $this->getThreeDigitCode();
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
}
