<?php

namespace Fitch\TutorBundle\Model\Interfaces;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\CompetencyLevel;

interface CompetencyLevelManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return CompetencyLevel
     */
    public function findById($id);

    /**
     * @return CompetencyLevel[]
     */
    public function findAll();

    /**
     * @return array
     */
    public function buildChoices();

    /**
     * Returns all active competencyLevels as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * (there's no obvious grouping, so its a flat list for CompetencyLevel)
     *
     * @return array
     */
    public function buildGroupedChoices();

    /**
     * @param $levelName
     *
     * @return CompetencyLevel
     */
    public function findOrCreate($levelName);

    /**
     * @param CompetencyLevel $competencyLevel
     * @param bool            $withFlush
     */
    public function saveCompetencyLevel($competencyLevel, $withFlush = true);

    /**
     * Create a new CompetencyLevel.
     *
     * Set its default values
     *
     * @return CompetencyLevel
     */
    public function createCompetencyLevel();

    /**
     * @param int $id
     */
    public function removeCompetencyLevel($id);

    /**
     * @param CompetencyLevel $competencyLevel
     */
    public function refreshCompetencyLevel(CompetencyLevel $competencyLevel);
}
