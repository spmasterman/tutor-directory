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
 * Proficiency.
 *
 * @ORM\Table(name="proficiency")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\ProficiencyRepository")
 */
class Proficiency implements
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
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=16)
     */
    protected $color = '#cccccc';

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     *
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }
}
