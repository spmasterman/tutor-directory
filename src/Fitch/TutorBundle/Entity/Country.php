<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;

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
     * @ORM\OneToOne(
     *      targetEntity="OperatingRegion"
     * )
     * @ORM\JoinColumn(name="default_region_id", referencedColumnName="id", onDelete="SET NULL")
     * @var OperatingRegion
     */
    protected $defaultRegion;

    /**
     * @ORM\Column(name="highlight", type="boolean")
     *
     * @var boolean
     */
    protected $highlighted;
}
