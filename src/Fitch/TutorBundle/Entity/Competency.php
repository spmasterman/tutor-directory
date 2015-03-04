<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityEntityTrait;
use Fitch\CommonBundle\Entity\IdentityEntityInterface;
use Fitch\CommonBundle\Entity\TimestampableEntityTrait;
use Fitch\CommonBundle\Entity\TimestampableEntityInterface;

/**
 * Competency.
 *
 * @ORM\Table(name="competency")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\CompetencyRepository")
 */
class Competency implements
    IdentityEntityInterface,
    TimestampableEntityInterface
{
    use IdentityEntityTrait, TimestampableEntityTrait;

    const NOT_YET_SPECIFIED = 'Unspecified';

    /**
     * @ORM\ManyToOne(targetEntity="Tutor", inversedBy="competencies")
     * @ORM\JoinColumn(name="tutor_id", referencedColumnName="id")
     *
     * @var Tutor
     */
    protected $tutor;

    /**
     * @ORM\ManyToOne(targetEntity="CompetencyType")
     * @ORM\JoinColumn(name="competency_type_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @var CompetencyType
     */
    protected $competencyType;

    /**
     * @ORM\ManyToOne(targetEntity="CompetencyLevel")
     * @ORM\JoinColumn(name="competency_level_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @var CompetencyLevel
     */
    protected $competencyLevel;

    /**
     * @ORM\Column(name="note", type="text", nullable=true)
     *
     * @var string;
     */
    protected $note;

    public function __toString()
    {
        $type = $this->getCompetencyType() ? $this->getCompetencyType()->getName() : self::NOT_YET_SPECIFIED;
        $level = $this->getCompetencyLevel() ? $this->getCompetencyLevel()->getName() : self::NOT_YET_SPECIFIED;

        return sprintf("%s (%s)", $type, $level);
    }

    /**
     * @return CompetencyLevel
     */
    public function getCompetencyLevel()
    {
        return $this->competencyLevel;
    }

    /**
     * @param CompetencyLevel $competencyLevel
     *
     * @return $this
     */
    public function setCompetencyLevel($competencyLevel)
    {
        $this->competencyLevel = $competencyLevel;

        return $this;
    }

    /**
     * @return CompetencyType
     */
    public function getCompetencyType()
    {
        return $this->competencyType;
    }

    /**
     * @param CompetencyType $competencyType
     *
     * @return $this
     */
    public function setCompetencyType($competencyType)
    {
        $this->competencyType = $competencyType;

        return $this;
    }

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
