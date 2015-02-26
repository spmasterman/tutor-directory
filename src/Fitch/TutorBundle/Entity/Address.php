<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;

/**
 * Address.
 *
 * Going with US naming conventions, because that's what people are used to dealing with on websites.
 * Feel free to get All-Anglican on this code if you feel strongly enough.
 *
 * @ORM\Table(name="address")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\AddressRepository")
 */
class Address implements
    IdentityTraitInterface,
    TimestampableTraitInterface
{
    use IdentityTrait, TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Tutor", inversedBy="addresses")
     * @ORM\JoinColumn(name="tutor_id", referencedColumnName="id")
     *
     * @var Tutor
     */
    protected $tutor;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=32)
     */
    protected $type = 'Office';

    /**
     * @var string
     *
     * @ORM\Column(name="street_primary", type="string", length=255, nullable=true)
     */
    protected $streetPrimary;

    /**
     * @var string
     *
     * @ORM\Column(name="street_secondary", type="string", length=255, nullable=true)
     */
    protected $streetSecondary;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255, nullable=true)
     */
    protected $state;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=32, nullable=true)
     */
    protected $zip;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     *
     * @var Country
     */
    protected $country;

    /**
     * @return string
     */
    public function __toString()
    {
        $bits = [
            $this->getStreetPrimary().', ',
            $this->getStreetSecondary().', ',
            $this->getCity().', ',
            $this->getState().' ',
            $this->getZip().' ',
            $this->getCountry()->getName()
        ];

        $outputString = '';
        foreach($bits as $bit) {
            if (strlen($bit) > 1) {
                $outputString .= $bit;
            }
        }
        return $outputString;
    }

    /**
     * Set #streetPrimary.
     *
     * @param string $streetPrimary
     *
     * @return $this
     */
    public function setStreetPrimary($streetPrimary)
    {
        $this->streetPrimary = $streetPrimary;

        return $this;
    }

    /**
     * Get streetPrimary.
     *
     * @return string
     */
    public function getStreetPrimary()
    {
        return $this->streetPrimary;
    }

    /**
     * Set streetSecondary.
     *
     * @param string $streetSecondary
     *
     * @return $this
     */
    public function setStreetSecondary($streetSecondary)
    {
        $this->streetSecondary = $streetSecondary;

        return $this;
    }

    /**
     * Get streetSecondary.
     *
     * @return string
     */
    public function getStreetSecondary()
    {
        return $this->streetSecondary;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state.
     *
     * @param string $state
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state.
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set zip.
     *
     * @param string $zip
     *
     * @return $this
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip.
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
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
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

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
     *
     * @return $this
     */
    public function setTutor($tutor)
    {
        $this->tutor = $tutor;

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
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }
}
