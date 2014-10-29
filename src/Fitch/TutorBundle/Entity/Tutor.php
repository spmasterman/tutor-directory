<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     *
     * @var Status
     */
    private $status;

    /**
     * @var ArrayCollection
     *
     * INVERSE SIDE
     * @ORM\OneToMany(targetEntity="Address",
     *      mappedBy="tutor",
     *      indexBy="id",
     *      cascade={"persist", "remove"}
     * )
     */
    private $addresses;

    /**
     * @var ArrayCollection
     *
     * INVERSE SIDE
     * @ORM\OneToMany(targetEntity="Phone",
     *      mappedBy="tutor",
     *      indexBy="id",
     *      cascade={"persist", "remove"}
     * )
     */
    protected $phoneNumbers;

    /**
     * @var ArrayCollection
     *
     * INVERSE SIDE
     * @ORM\OneToMany(targetEntity="Email",
     *      mappedBy="tutor",
     *      indexBy="id",
     *      cascade={"persist", "remove"}
     * )
     */
    protected $emailAddresses;

    /**
     * @var ArrayCollection
     *
     * INVERSE SIDE
     * @ORM\OneToMany(targetEntity="File",
     *      mappedBy="tutor",
     *      indexBy="id",
     *      cascade={"persist", "remove"}
     * )
     */
    protected $files;

    /**
     * @var ArrayCollection
     *
     * INVERSE SIDE
     * @ORM\OneToMany(targetEntity="Competency",
     *      mappedBy="tutor",
     *      indexBy="id",
     *      cascade={"persist", "remove"}
     * )
     */
    protected $competencies;

    /**
     * @ORM\ManyToOne(targetEntity="OperatingRegion")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     *
     * @var OperatingRegion
     */
    protected $region;


    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->emailAddresses = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param ArrayCollection $addresses
     * @return $this
     */
    public function setAddresses($addresses)
    {
        foreach ($addresses as $address) {
            $this->addAddress($address);
        }
        return $this;
    }

    /**
     * @param Address $address
     * @return $this
     */
    public function addAddress (Address $address)
    {
        $this->addresses->add($address);
        $address->setTutor($this);
        return $this;
    }

    /**
     * @param Address $address
     * @return $this
     */
    public function removeAddress(Address $address)
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
        }
        return $this;
    }

    public function hasAddress()
    {
        return $this->addresses->count() > 0;
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
     * @return OperatingRegion
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param OperatingRegion $region
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;
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

    /**
     * @param Competency $competency
     * @return $this
     */
    public function addCompetency (Competency $competency)
    {
        if (! $this->competencies->contains($competency)) {
            $competency->setTutor($this);
            $this->competencies->add($competency);
        }
        return $this;
    }

    /**
     * @param Email $email
     * @return $this
     */
    public function removeEmail(Email $email)
    {
        if ($this->emailAddresses->contains($email)) {
            $this->emailAddresses->removeElement($email);
        }
        return $this;
    }

    /**
     * @param File $file
     * @return $this
     */
    public function removeFile(File $file)
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
        }
        return $this;
    }

    /**
     * @param Phone $phone
     * @return $this
     */
    public function removePhoneNumber(Phone $phone)
    {
        if ($this->phoneNumbers->contains($phone)) {
            $this->phoneNumbers->removeElement($phone);
        }
        return $this;
    }

    /**
     * @param Competency $competency
     * @return $this
     */
    public function removeCompetency(Competency $competency)
    {
        if ($this->competencies->contains($competency)) {
            $this->competencies->removeElement($competency);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}
