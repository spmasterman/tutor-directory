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
 * FileType.
 *
 * @ORM\Table(name="file_type")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\FileTypeRepository")
 */
class FileType implements
    IdentityTraitInterface,
    TimestampableTraitInterface,
    NamedTraitInterface,
    DefaultTraitInterface
{
    use IdentityTrait, TimestampableTrait, NamedTrait, DefaultTrait;

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
     * @ORM\Column(name="name", type="string", length=64)
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
