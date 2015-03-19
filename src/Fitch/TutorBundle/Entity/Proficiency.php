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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Proficiency.
 *
 * @ORM\Table(name="proficiency")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\ProficiencyRepository")
 * @UniqueEntity("name")
 */
class Proficiency implements
    IdentityEntityInterface,
    TimestampableEntityInterface,
    NamedEntityInterface,
    DefaultableEntityInterface
{
    use IdentityEntityTrait, TimestampableEntityTrait, NamedEntityTrait, DefaultableEntityTrait;

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
