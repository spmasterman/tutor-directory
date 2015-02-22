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
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Email.
 *
 * @ORM\Table(name="status")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\StatusRepository")
 */
class Status implements
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
     * @ORM\OneToMany(targetEntity="Tutor", mappedBy="status")
     * @ORM\JoinColumn(name="tutor_id", referencedColumnName="id")
     *
     * @var Tutor
     */
    protected $tutor;
}
