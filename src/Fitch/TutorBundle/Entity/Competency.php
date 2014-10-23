<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Competency
 *
 * @ORM\Table(name="competency")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\Competency")
 */
class Competency implements IdentityTraitInterface, TimestampableTraitInterface
{
    use IdentityTrait, TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Tutor", inversedBy="competencies")
     * @ORM\JoinColumn(name="tutor_id", referencedColumnName="id")
     *
     * @var Tutor
     */
    protected $tutor;

    /**
     * @ORM\ManyToOne(targetEntity="CompetencyType")
     * @ORM\JoinColumn(name="competency_type_id", referencedColumnName="id")
     *
     * @var CompetencyType
     */
    protected $competencyType;

    /**
     * @ORM\ManyToOne(targetEntity="CompetencyLevel")
     * @ORM\JoinColumn(name="competency_level_id", referencedColumnName="id")
     *
     * @var CompetencyLevel
     */
    protected $competencyLevel;

    /**
     * @return CompetencyLevel
     */
    public function getCompetencyLevel()
    {
        return $this->competencyLevel;
    }

    /**
     * @param CompetencyLevel $competencyLevel
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
     * @return $this
     */
    public function setCompetencyType($competencyType)
    {
        $this->competencyType = $competencyType;
        return $this;
    }
}
