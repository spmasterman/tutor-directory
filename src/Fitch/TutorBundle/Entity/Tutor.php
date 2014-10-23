<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;

/**
 * Tutor
 *
 * @ORM\Table(name="tutor")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\TutorRepository")
 */
class Tutor implements IdentityTraitInterface, TimestampableTraitInterface
{
    use IdentityTrait, TimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * INVERSE SIDE
     * @ORM\OneToMany(targetEntity="Phone",
     *      mappedBy="tutor",
     *      indexBy="id"
     * )
     */
    protected $phoneNumbers;

    /**
     * @var ArrayCollection
     *
     * INVERSE SIDE
     * @ORM\OneToMany(targetEntity="Email",
     *      mappedBy="tutor",
     *      indexBy="id"
     * )
     */
    protected $emailAddresses;

    /**
     * @var ArrayCollection
     *
     * INVERSE SIDE
     * @ORM\OneToMany(targetEntity="File",
     *      mappedBy="tutor",
     *      indexBy="id"
     * )
     */
    protected $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
        $this->emailAddresses = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getEmailAddresses()
    {
        return $this->emailAddresses;
    }

    /**
     * @param ArrayCollection $emailAddresses
     * @return $this
     */
    public function setEmailAddresses($emailAddresses)
    {
        $this->emailAddresses = $emailAddresses;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param ArrayCollection $files
     * @return $this
     */
    public function setFiles($files)
    {
        $this->files = $files;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }

    /**
     * @param ArrayCollection $phoneNumbers
     * @return $this
     */
    public function setPhoneNumbers($phoneNumbers)
    {
        $this->phoneNumbers = $phoneNumbers;
        return $this;
    }

    /**
     * @param Phone $phoneNumber
     * @return $this
     */
    public function addPhoneNumber(Phone $phoneNumber)
    {
        if (! $this->phoneNumbers->contains($phoneNumber)) {
            $phoneNumber->setTutor($this);
            $this->phoneNumbers->add($phoneNumber);
        }
        return $this;
    }

    /**
     * @param Email $emailAddress
     * @return $this
     */
    public function addEmailAddress(Email $emailAddress)
    {
        if (! $this->emailAddresses->contains($emailAddress)) {
            $emailAddress->setTutor($this);
            $this->emailAddresses->add($emailAddress);
        }
        return $this;
    }

    /**
     * @param File $file
     * @return $this
     */
    public function addFile (File $file)
    {
        if (! $this->files->contains($file)) {
            $file->setTutor($this);
            $this->files->add($file);
        }
        return $this;
    }
}
