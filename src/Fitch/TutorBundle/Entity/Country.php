<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\ActiveAndPreferredEntityTrait;
use Fitch\CommonBundle\Entity\ActiveAndPreferredEntityInterface;
use Fitch\CommonBundle\Entity\IdentityEntityTrait;
use Fitch\CommonBundle\Entity\IdentityEntityInterface;
use Fitch\CommonBundle\Entity\NamedEntityTrait;
use Fitch\CommonBundle\Entity\NamedEntityInterface;
use Fitch\CommonBundle\Entity\TimestampableEntityTrait;
use Fitch\CommonBundle\Entity\TimestampableEntityInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Country.
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\CountryRepository")
 * @UniqueEntity("name")
 * @UniqueEntity("twoDigitCode")
 *
 */
class Country implements
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
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_code_two", type="string", length=2, unique=true)
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
     * @Assert\Regex(
     *  pattern
     *    ="/(^$)|(^\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|4[987654310]|3[9643210]|2[70]|7|1)$)/",
     *  message
     *    ="Please enter a correct ITU-T E.164 dialling code, with a leading + (i.e. +44 for the UK, +1 for the US)"
     * )
     *
     * -- obvious really!
     *      Search ITU-T E.164 and Annex to ITU Operational Bulletin No. 930 – 15.IV.2009 :o)
     * Thanks StackOverflow.
     *
     * Note this is not Unique - countries share codes (+1 being a prime example)
     */
    protected $dialingCode;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="OperatingRegion"
     * )
     * @ORM\JoinColumn(name="default_region_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @var OperatingRegion
     */
    protected $defaultRegion;

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s (%s) %s', $this->getName(), $this->getThreeDigitCode(), $this->getDialingCode());
    }

    /**
     * Loads up an object from a named array, via its setters
     *
     * @param array $data
     */
    public function fromArray(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . Inflector::classify($key);

            if (method_exists($this, $method)) {
                call_user_func(array($this, $method), $value);
            }
        }
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
     *
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
     *
     * @return $this
     */
    public function setDialingCode($dialingCode)
    {
        $this->dialingCode = $dialingCode;

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
     *
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
     *
     * @return $this
     */
    public function setTwoDigitCode($twoDigitCode)
    {
        $this->twoDigitCode = $twoDigitCode;

        return $this;
    }
}
