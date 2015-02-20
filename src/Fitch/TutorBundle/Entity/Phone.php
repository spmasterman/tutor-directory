<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Phone
 *
 * @ORM\Table(name="phone")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\PhoneRepository")
 */
class Phone implements IdentityTraitInterface, TimestampableTraitInterface
{
    use IdentityTrait, TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Tutor", inversedBy="phoneNumbers")
     * @ORM\JoinColumn(name="tutor_id", referencedColumnName="id")
     *
     * @var Tutor
     */
    protected $tutor;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     *
     * @var Country
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=32)
     */
    protected $number;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=32)
     */
    protected $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="preferred", type="boolean")
     */
    protected $preferred;

    /**
     * @return string
     */
    public function __toString()
    {
        $stringRepresentation = $this->getFullNumber();

        if ($this->getType()) {
            $stringRepresentation .= ' (' . $this->getType() . ')';
        }

        return $stringRepresentation;
    }

    /**
     * @return string
     */
    public function getFullNumber()
    {
        $stringRepresentation = '';
        if ($this->getCountry()) {
            $stringRepresentation = $this->getCountry()->getDialingCode() . ' ' . $stringRepresentation;
        }

        $stringRepresentation .= $this->getNumber();
        return $stringRepresentation;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return Tutor
     */
    public function getTutor()
    {
        return $this->tutor;
    }

    /**
     * @param Tutor $tutor
     * @return $this
     */
    public function setTutor($tutor)
    {
        $this->tutor = $tutor;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
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
