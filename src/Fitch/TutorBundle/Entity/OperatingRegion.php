<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\DefaultTrait;
use Fitch\CommonBundle\Entity\DefaultTraitInterface;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\NamedTrait;
use Fitch\CommonBundle\Entity\NamedTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;

/**
 * OperatingRegion.
 *
 * @ORM\Table(name="region")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\OperatingRegionRepository")
 */
class OperatingRegion implements
    IdentityTraitInterface,
    TimestampableTraitInterface,
    NamedTraitInterface,
    DefaultTraitInterface
{
    use IdentityTrait, TimestampableTrait, NamedTrait, DefaultTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="Currency"
     * )
     * @ORM\JoinColumn(name="default_currency_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @var Currency
     */
    protected $defaultCurrency;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=5)
     */
    protected $code;

    /**
     * @return Currency
     */
    public function getDefaultCurrency()
    {
        return $this->defaultCurrency;
    }

    /**
     * @param Currency $defaultCurrency
     *
     * @return $this
     */
    public function setDefaultCurrency($defaultCurrency)
    {
        $this->defaultCurrency = $defaultCurrency;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
}
