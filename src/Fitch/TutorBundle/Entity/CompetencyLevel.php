<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityEntityTrait;
use Fitch\CommonBundle\Entity\IdentityEntityInterface;
use Fitch\CommonBundle\Entity\NamedEntityTrait;
use Fitch\CommonBundle\Entity\NamedEntityInterface;
use Fitch\CommonBundle\Entity\TimestampableEntityTrait;
use Fitch\CommonBundle\Entity\TimestampableEntityInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CompetencyLevel.
 *
 * @ORM\Table(name="competency_level")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\CompetencyLevelRepository")
 * @UniqueEntity("name")
 */
class CompetencyLevel implements
    IdentityEntityInterface,
    TimestampableEntityInterface,
    NamedEntityInterface
{
    use IdentityEntityTrait, TimestampableEntityTrait, NamedEntityTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32, unique=true)
     * @Assert\NotBlank()
     */
    protected $name = 'unspecified';

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
