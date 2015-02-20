<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\NamedTrait;
use Fitch\CommonBundle\Entity\NamedTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Tutor
 *
 * @ORM\Table(name="tutor")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\TutorRepository")
 */
class Tutor implements IdentityTraitInterface, TimestampableTraitInterface, NamedTraitInterface
{
    use IdentityTrait, TimestampableTrait, NamedTrait;

    // These constants are used to control access to individual tutors via the TutorVoter class.
    const ACCESS_LEVEL_LIMITED_VIEW = 'Limited View';
    const ACCESS_LEVEL_FULL_VIEW = 'Full View';
    const ACCESS_LEVEL_LIMITED_EDIT = 'Limited Edit';
    const ACCESS_LEVEL_FULL_EDIT = 'Full Edit';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="TutorType")
     * @ORM\JoinColumn(name="tutor_type_id", referencedColumnName="id")
     *
     * @var TutorType
     */
    protected $tutorType;

    /**
     * @var string
     *
     * @ORM\Column(name="linkedin_url", type="string", length=255, nullable=true)
     */
    protected $linkedInURL;

    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="text", nullable=true)
     */
    protected $bio;

    /**
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="tutor")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     *
     * @var Status
     */
    protected $status;

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
    protected $addresses;

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
     * @var ArrayCollection
     *
     * INVERSE SIDE
     * @ORM\OneToMany(targetEntity="TutorLanguage",
     *      mappedBy="tutor",
     *      indexBy="id",
     *      cascade={"persist", "remove"},
     *      orphanRemoval=true
     * )
     */
    protected $tutorLanguages;

    /**
     * @var ArrayCollection
     *
     * INVERSE SIDE
     * @ORM\OneToMany(targetEntity="Note",
     *      mappedBy="tutor",
     *      indexBy="id",
     *      cascade={"persist", "remove"}
     * )
     */
    protected $notes;
    
    /**
     * @ORM\ManyToOne(targetEntity="OperatingRegion")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     *
     * @var OperatingRegion
     */
    protected $region;

    /**
     * @ORM\ManyToOne(targetEntity="Currency")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     *
     * @var Currency
     */
    protected $currency;

    /**
     * @var ArrayCollection
     *
     * INVERSE SIDE
     * @ORM\OneToMany(targetEntity="Rate",
     *      mappedBy="tutor",
     *      indexBy="id",
     *      cascade={"persist", "remove"}
     * )
     */
    protected $rates;
    
    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->emailAddresses = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->rates = new ArrayCollection();
        $this->competencies = new ArrayCollection();
        $this->tutorLanguages = new ArrayCollection();
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
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
        }
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

    /**
     * @return bool
     */
    public function hasAddress()
    {
        return $this->addresses->count() > 0;
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
        foreach($phoneNumbers as $phoneNumber) {
            $this->addPhoneNumber($phoneNumber);
        }
        return $this;
    }

    /**
     * @param Phone $phoneNumber
     * @return $this
     */
    public function addPhoneNumber(Phone $phoneNumber)
    {
        if (!$this->phoneNumbers->contains($phoneNumber)) {
            $this->phoneNumbers->add($phoneNumber);
        }
        $phoneNumber->setTutor($this);
        return $this;
    }

    /**
     * @param Phone $phoneNumber
     * @return $this
     */
    public function removePhoneNumber(Phone $phoneNumber)
    {
        if ($this->phoneNumbers->contains($phoneNumber)) {
            $this->phoneNumbers->removeElement($phoneNumber);
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function hasPhoneNumber()
    {
        return $this->phoneNumbers->count() > 0;
    }

    /**
     * @return ArrayCollection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param ArrayCollection $notes
     * @return $this
     */
    public function setNotes($notes)
    {
        foreach ($notes as $note) {
            $this->addNote($note);
        }
        return $this;
    }

    /**
     * @param Note $note
     * @return $this
     */
    public function addNote (Note $note)
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
        }
        $note->setTutor($this);
        return $this;
    }

    /**
     * @param Note $note
     * @return $this
     */
    public function removeNote(Note $note)
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function hasNote()
    {
        return $this->notes->count() > 0;
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
        foreach ($emailAddresses as $emailAddress) {
            $this->addEmailAddress($emailAddress);
        }
        return $this;
    }

    /**
     * @param Email $emailAddress
     * @return $this
     */
    public function addEmailAddress(Email $emailAddress)
    {
        if (!$this->emailAddresses->contains($emailAddress)) {
            $this->emailAddresses->add($emailAddress);
        }
        $emailAddress->setTutor($this);
        return $this;
    }

    /**
     * @param Email $emailAddress
     * @return $this
     */
    public function removeEmailAddress(Email $emailAddress)
    {
        if ($this->emailAddresses->contains($emailAddress)) {
            $this->emailAddresses->removeElement($emailAddress);
        }
        return $this;
    }

    /**
     * @return File[]
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
        foreach($files as $file) {
            $this->addFile($file);
        }
        return $this;
    }

    /**
     * @param File $file
     * @return $this
     */
    public function addFile (File $file)
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
        }
        $file->setTutor($this);
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
     * @return ArrayCollection
     */
    public function getCompetencies()
    {
        return $this->competencies;
    }

    /**
     * @param ArrayCollection $competencies
     * @return $this
     */
    public function setCompetencies($competencies)
    {
        foreach($competencies as $competency) {
            $this->addCompetency($competency);
        }
        return $this;
    }

    /**
     * @param Competency $competency
     * @return $this
     */
    public function addCompetency(Competency $competency)
    {
        if (!$this->competencies->contains($competency)) {
            $this->competencies->add($competency);
        }
        $competency->setTutor($this);
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
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param Status $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @param string $bio
     * @return $this
     */
    public function setBio($bio)
    {
        $this->bio = $bio;
        return $this;
    }

    /**
     * @return string
     */
    public function getLinkedInURL()
    {
        return $this->linkedInURL;
    }

    /**
     * @param string $linkedInURL
     * @return $this
     */
    public function setLinkedInURL($linkedInURL)
    {
        $this->linkedInURL = $linkedInURL;
        return $this;
    }

    /**
     * @return TutorType
     */
    public function getTutorType()
    {
        return $this->tutorType;
    }

    /**
     * @param TutorType $tutorType
     * @return $this
     */
    public function setTutorType($tutorType)
    {
        $this->tutorType = $tutorType;
        return $this;
    }

    /**
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }


    /**
     * @return ArrayCollection
     */
    public function getRates()
    {
        return $this->rates;
    }

    /**
     * @param ArrayCollection $rates
     * @return $this
     */
    public function setRates($rates)
    {
        foreach($rates as $rate) {
            $this->addRate($rate);
        }
        return $this;
    }

    /**
     * @param Rate $rate
     * @return $this
     */
    public function addRate (Rate $rate)
    {
        if (!$this->rates->contains($rate)) {
            $this->rates->add($rate);
        }
        $rate->setTutor($this);
        return $this;
    }

    /**
     * @param Rate $rate
     * @return $this
     */
    public function removeRate(Rate $rate)
    {
        if ($this->rates->contains($rate)) {
            $this->rates->removeElement($rate);
        }
        return $this;
    }
    
    /**
     * @return File|null
     */
    public function getProfilePicture()
    {
        foreach($this->getFiles() as $file) {
            if ($file->isImage()) {
                $fileType = $file->getFileType();
                if ($fileType->isSuitableForProfilePicture() && ! $fileType->isPrivate()) {
                    return $file;
                }
            }
        }
        return null;
    }

    /**
     * @return ArrayCollection
     */
    public function getTutorLanguages()
    {
        return $this->tutorLanguages;
    }

    /**
     * @param ArrayCollection $languages
     * @return $this
     */
    public function setTutorLanguages($languages)
    {
        $this->tutorLanguages = $languages;
        return $this;
    }

    /**
     * @param TutorLanguage $tutorLanguage
     * @return $this
     */
    public function addTutorLanguage (TutorLanguage $tutorLanguage)
    {
        $tutorLanguage->setTutor($this);
        if (!$this->tutorLanguages->contains($tutorLanguage)) {
            $this->tutorLanguages->add($tutorLanguage);
        }
        return $this;
    }

    /**
     * @param TutorLanguage $tutorLanguage
     * @return $this
     */
    public function removeTutorLanguage(TutorLanguage $tutorLanguage)
    {
        if ($this->tutorLanguages->contains($tutorLanguage)) {
            $this->tutorLanguages->removeElement($tutorLanguage);
        }
        return $this;
    }
}
