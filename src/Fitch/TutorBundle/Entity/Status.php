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
 * Email.
 *
 * @ORM\Table(name="status")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\StatusRepository")
 * @UniqueEntity("name")
 */
class Status implements
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
}
