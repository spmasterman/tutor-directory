<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\ActiveAndPreferredEntityTrait;
use Fitch\CommonBundle\Entity\ActiveAndPreferredEntityInterface;
use Fitch\CommonBundle\Entity\IdentityEntityTrait;
use Fitch\CommonBundle\Entity\IdentityEntityInterface;
use Fitch\CommonBundle\Entity\NamedEntityTrait;
use Fitch\CommonBundle\Entity\NamedEntityInterface;
use Fitch\CommonBundle\Entity\TimestampableEntityTrait;
use Fitch\CommonBundle\Entity\TimestampableEntityInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Language.
 *
 * @ORM\Table(name="language")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\LanguageRepository")
 * @UniqueEntity("name")
 * @UniqueEntity("threeLetterCode")
 */
class Language implements
    IdentityEntityInterface,
    TimestampableEntityInterface,
    NamedEntityInterface,
    ActiveAndPreferredEntityInterface
{
    use IdentityEntityTrait, TimestampableEntityTrait, NamedEntityTrait, ActiveAndPreferredEntityTrait;

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
     * @ORM\Column(name="name", type="string", length=64,  unique=true)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="three_letter_code", type="string", length=3, unique=true)
     * @Assert\NotBlank()
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
