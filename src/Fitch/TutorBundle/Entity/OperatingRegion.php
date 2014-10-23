<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;

/**
 * OperatingRegion
 *
 * @ORM\Table(name="region")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\OperatingRegionRepository")
 */
class OperatingRegion implements IdentityTraitInterface, TimestampableTraitInterface
{
    use IdentityTrait, TimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    protected $name;

}
