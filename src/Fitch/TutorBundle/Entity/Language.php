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
 * Language
 *
 * @ORM\Table(name="language")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\LanguageRepository")
 */
class Language implements IdentityTraitInterface, TimestampableTraitInterface
{
    use IdentityTrait, TimestampableTrait;

    const NOT_YET_SPECIFIED = 'Unspecified';

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Tutor", mappedBy="languages")
     */
    protected $tutors;

    /**
     * @ORM\Column(name="name", type="string", length=100)
     *
     * @var string;
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="three_letter_code", type="string", length=3)
     */
    protected $threeLetterCode;

    /**
     * @ORM\Column(name="preferred", type="boolean")
     *
     * @var boolean
     */
    protected $preferred;

    /**
     * @ORM\Column(name="active", type="boolean")
     *
     * @var boolean
     */
    protected $active = true;

    /**
     *
     */
    public function __construct()
    {
        $this->tutors = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->name;
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
     * @return mixed
     */
    public function getTutors()
    {
        return $this->tutors;
    }

    /**
     * @param mixed $tutors
     * @return $this
     */
    public function setTutors($tutors)
    {
        $this->tutors = $tutors;
        return $this;
    }

    /**
     * @param Tutor $tutor
     * @return $this
     */
    public function addTutor (Tutor $tutor)
    {
        if (!$this->tutors->contains($tutor)) {
            $this->tutors->add($tutor);
        }
        return $this;
    }

    /**
     * @param Tutor $tutor
     * @return $this
     */
    public function removeTutor(Tutor $tutor)
    {
        if ($this->tutors->contains($tutor)) {
            $this->tutors->removeElement($tutor);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getThreeLetterCode()
    {
        return $this->threeLetterCode;
    }

    /**
     * @param string $threeLetterCode
     * @return $this
     */
    public function setThreeLetterCode($threeLetterCode)
    {
        $this->threeLetterCode = $threeLetterCode;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPreferred()
    {
        return $this->preferred;
    }

    /**
     * @param boolean $preferred
     * @return $this
     */
    public function setPreferred($preferred)
    {
        $this->preferred = $preferred;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }
}
