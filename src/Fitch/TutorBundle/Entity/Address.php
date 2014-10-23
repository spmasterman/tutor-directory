<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Address
 *
 * Going with US naming conventions, because that's what people are used to dealing with on websites.
 * Feel free to get All-Anglican on this code if you feel strongly enough.
 *
 * @ORM\Table(name="address")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\AddressRepository")
 */
class Address implements IdentityTraitInterface, TimestampableTraitInterface
{
    use IdentityTrait, TimestampableTrait;


    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=32)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="street_primary", type="string", length=255)
     */
    private $streetPrimary;

    /**
     * @var string
     *
     * @ORM\Column(name="street_secondary", type="string", length=255)
     */
    private $streetSecondary;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=32)
     */
    private $zip;

    /**
     * Set #streetPrimary
     *
     * @param string $streetPrimary
     * @return Address
     */
    public function setStreetPrimary($streetPrimary)
    {
        $this->streetPrimary = $streetPrimary;

        return $this;
    }

    /**
     * Get streetPrimary
     *
     * @return string 
     */
    public function getStreetPrimary()
    {
        return $this->streetPrimary;
    }

    /**
     * Set streetSecondary
     *
     * @param string $streetSecondary
     * @return Address
     */
    public function setStreetSecondary($streetSecondary)
    {
        $this->streetSecondary = $streetSecondary;

        return $this;
    }

    /**
     * Get streetSecondary
     *
     * @return string 
     */
    public function getStreetSecondary()
    {
        return $this->streetSecondary;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Address
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set zip
     *
     * @param string $zip
     * @return Address
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
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
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


}
