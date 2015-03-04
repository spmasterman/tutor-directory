<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\DefaultableEntityTrait;
use Fitch\CommonBundle\Entity\DefaultableEntityInterface;
use Fitch\CommonBundle\Entity\IdentityEntityTrait;
use Fitch\CommonBundle\Entity\IdentityEntityInterface;
use Fitch\CommonBundle\Entity\NamedEntityTrait;
use Fitch\CommonBundle\Entity\NamedEntityInterface;
use Fitch\CommonBundle\Entity\TimestampableEntityTrait;
use Fitch\CommonBundle\Entity\TimestampableEntityInterface;

/**
 * OperatingRegion.
 *
 * @ORM\Table(name="region")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\OperatingRegionRepository")
 */
class OperatingRegion implements
    IdentityEntityInterface,
    TimestampableEntityInterface,
    NamedEntityInterface,
    DefaultableEntityInterface
{
    use IdentityEntityTrait, TimestampableEntityTrait, NamedEntityTrait, DefaultableEntityTrait;

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
