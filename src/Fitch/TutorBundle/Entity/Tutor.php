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
    private $linkedInURL;

    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="text", nullable=true)
     */
    private $bio;

    /**
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="tutor")
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
     * @ORM\Column(name="day_rate", type="decimal", precision=8, scale=2)
     *
     * @var string
     */
    protected $dayRate = '0.00';

    /**
     * @ORM\Column(name="evening_rate", type="decimal", precision=8, scale=2)
     *
     * @var string
     */
    protected $eveningRate = '0.00';

    /**
     * @ORM\Column(name="travel_rate", type="decimal", precision=8, scale=2)
     *
     * @var string
     */
    protected $travelRate = '0.00';

    /**
     * @ORM\Column(name="material_rate", type="decimal", precision=8, scale=2)
     *
     * @var string
     */
    protected $materialRate = '0.00';

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->emailAddresses = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
        $this->notes = new ArrayCollection();
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
        $phoneNumber->setTutor($this);
        $this->phoneNumbers->add($phoneNumber);
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
        $this->notes->add($note);
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
        $emailAddress->setTutor($this);
        $this->emailAddresses->add($emailAddress);
        return $this;
    }

    /**
     * @param Email $email
     * @return $this
     */
    public function removeEmailAddress(Email $email)
    {
        if ($this->emailAddresses->contains($email)) {
            $this->emailAddresses->removeElement($email);
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
        $file->setTutor($this);
        $this->files->add($file);
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
    public function addCompetency (Competency $competency)
    {
        $competency->setTutor($this);
        $this->competencies->add($competency);
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
     * @return string
     */
    public function getDayRate()
    {
        return $this->dayRate;
    }

    /**
     * @param string $dayRate
     * @return $this
     */
    public function setDayRate($dayRate)
    {
        $this->dayRate = $dayRate;
        return $this;
    }

    /**
     * @return string
     */
    public function getEveningRate()
    {
        return $this->eveningRate;
    }

    /**
     * @param string $eveningRate
     * @return $this
     */
    public function setEveningRate($eveningRate)
    {
        $this->eveningRate = $eveningRate;
        return $this;
    }

    /**
     * @return string
     */
    public function getMaterialRate()
    {
        return $this->materialRate;
    }

    /**
     * @param string $materialRate
     * @return $this
     */
    public function setMaterialRate($materialRate)
    {
        $this->materialRate = $materialRate;
        return $this;
    }

    /**
     * @return string
     */
    public function getTravelRate()
    {
        return $this->travelRate;
    }

    /**
     * @param string $travelRate
     * @return $this
     */
    public function setTravelRate($travelRate)
    {
        $this->travelRate = $travelRate;
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
     * @return File|null
     */
    public function getProfilePicture()
    {
        foreach($this->getFiles() as $file) {
            if ($file->isImage()) {
                $fileType = $file->getFileType();
                if ($fileType->isProfilePicture() && ! $fileType->isPrivate()) {
                    return $file;
                }
            }
        }
        return null;
    }
}
