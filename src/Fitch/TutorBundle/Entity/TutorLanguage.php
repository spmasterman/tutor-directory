<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityEntityTrait;
use Fitch\CommonBundle\Entity\IdentityEntityInterface;
use Fitch\CommonBundle\Entity\TimestampableEntityTrait;
use Fitch\CommonBundle\Entity\TimestampableEntityInterface;

/**
 * TutorLanguage.
 *
 * @ORM\Table(name="tutor_language")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\TutorLanguageRepository")
 */
class TutorLanguage implements
    IdentityEntityInterface,
    TimestampableEntityInterface
{
    use IdentityEntityTrait, TimestampableEntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Tutor", inversedBy="tutorLanguages")
     * @ORM\JoinColumn(name="tutor_id", referencedColumnName="id")
     *
     * @var Tutor
     */
    protected $tutor;

    /**
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="tutorLanguages")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     *
     * @var Language
     */
    protected $language;

    /**
     * @ORM\ManyToOne(targetEntity="Proficiency")
     * @ORM\JoinColumn(name="proficiency_id", referencedColumnName="id")
     *
     * @var Proficiency
     */
    protected $proficiency;

    /**
     * @ORM\Column(name="note", type="text", nullable=true)
     *
     * @var string;
     */
    protected $note;

    /**
     * @return Tutor
     */
    public function getTutor()
    {
        return $this->tutor;
    }

    /**
     * @param Tutor $tutor
     *
     * @return $this
     */
    public function setTutor($tutor)
    {
        $this->tutor = $tutor;

        return $this;
    }

    /**
     * @return Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param Language $language
     *
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return Proficiency
     */
    public function getProficiency()
    {
        return $this->proficiency;
    }

    /**
     * @param Proficiency $proficiency
     *
     * @return $this
     */
    public function setProficiency($proficiency)
    {
        $this->proficiency = $proficiency;

        return $this;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     *
     * @return $this
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }
}
