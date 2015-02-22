<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\ActiveAndPreferredTrait;
use Fitch\CommonBundle\Entity\ActiveAndPreferredTraitInterface;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\NamedTrait;
use Fitch\CommonBundle\Entity\NamedTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Language.
 *
 * @ORM\Table(name="language")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\LanguageRepository")
 */
class Language implements
    IdentityTraitInterface,
    TimestampableTraitInterface,
    NamedTraitInterface,
    ActiveAndPreferredTraitInterface
{
    use IdentityTrait, TimestampableTrait, NamedTrait, ActiveAndPreferredTrait;

    /**
     * @var ArrayCollection
     *
     * INVERSE SIDE
     * @ORM\OneToMany(targetEntity="TutorLanguage",
     *      mappedBy="language",
     *      indexBy="id",
     *      cascade={"remove"},
     *      orphanRemoval=true
     * )
     */
    protected $tutorLanguages;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="three_letter_code", type="string", length=3)
     */
    protected $threeLetterCode;

    /**
     *
     */
    public function __construct()
    {
        $this->tutorLanguages = new ArrayCollection();
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
     *
     * @return $this
     */
    public function setTutorLanguages($languages)
    {
        $this->tutorLanguages = $languages;

        return $this;
    }

    /**
     * @param TutorLanguage $tutorLanguage
     *
     * @return $this
     */
    public function addTutorLanguage(TutorLanguage $tutorLanguage)
    {
        if (!$this->tutorLanguages->contains($tutorLanguage)) {
            $this->tutorLanguages->add($tutorLanguage);
        }

        return $this;
    }

    /**
     * @param TutorLanguage $tutorLanguage
     *
     * @return $this
     */
    public function removeTutorLanguage(TutorLanguage $tutorLanguage)
    {
        if ($this->tutorLanguages->contains($tutorLanguage)) {
            $this->tutorLanguages->removeElement($tutorLanguage);
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
     *
     * @return $this
     */
    public function setThreeLetterCode($threeLetterCode)
    {
        $this->threeLetterCode = $threeLetterCode;

        return $this;
    }
}
