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
 * File.
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="rate")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\RateRepository")
 */
class Rate implements IdentityTraitInterface, TimestampableTraitInterface, NamedTraitInterface
{
    use IdentityTrait, TimestampableTrait, NamedTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Tutor", inversedBy="rates")
     * @ORM\JoinColumn(name="tutor_id", referencedColumnName="id")
     *
     * @var Tutor
     */
    protected $tutor;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @var string
     */
    protected $name;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(name="amount", type="decimal", precision=8, scale=2)
     *
     * @var string
     */
    protected $amount = '0.00';

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

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
}
