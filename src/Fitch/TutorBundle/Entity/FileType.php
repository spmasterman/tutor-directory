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
 * FileType.
 *
 * @ORM\Table(name="file_type")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\FileTypeRepository")
 * @UniqueEntity("name")
 */
class FileType implements
    IdentityEntityInterface,
    TimestampableEntityInterface,
    NamedEntityInterface,
    DefaultableEntityInterface
{
    use IdentityEntityTrait, TimestampableEntityTrait, NamedEntityTrait, DefaultableEntityTrait;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_private", type="boolean")
     */
    protected $private;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_profile_picture", type="boolean")
     */
    protected $suitableForProfilePicture;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_bio", type="boolean")
     */
    protected $displayWithBio;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, unique=true)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName().($this->isPrivate() ? ' (Private)' : '');
    }

    /**
     * @return boolean
     */
    public function isPrivate()
    {
        return $this->private;
    }

    /**
     * @param boolean $private
     *
     * @return $this
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSuitableForProfilePicture()
    {
        return $this->suitableForProfilePicture;
    }

    /**
     * @param boolean $isProfilePicture
     *
     * @return $this
     */
    public function setSuitableForProfilePicture($isProfilePicture)
    {
        $this->suitableForProfilePicture = $isProfilePicture;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isDisplayWithBio()
    {
        return $this->displayWithBio;
    }

    /**
     * @param boolean $displayWithBio
     *
     * @return $this
     */
    public function setDisplayWithBio($displayWithBio)
    {
        $this->displayWithBio = $displayWithBio;

        return $this;
    }
}
