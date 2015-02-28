<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Proficiency;

interface ProficiencyManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Proficiency
     */
    public function findById($id);

    /**
     * @return Proficiency[]
     */
    public function findAll();

    /**
     * @return null|Proficiency
     */
    public function findDefaultProficiency();

    /**
     * @param $proficiencyName
     *
     * @return Proficiency
     */
    public function findOrCreate($proficiencyName);

    /**
     * Returns all active competencyLevels as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * (there's no obvious grouping, so its a flat list for Proficiency)
     *
     * @return array
     */
    public function buildGroupedChoices();

    /**
     * @param Proficiency $proficiency
     * @param bool        $withFlush
     */
    public function saveEntity($proficiency, $withFlush = true);

    /**
     * Create a new Proficiency.
     *
     * Set its default values
     *
     * @return Proficiency
     */
    public function createEntity();

    /**
     * @param int $id
     */
    public function removeEntity($id);

    /**
     * @param Proficiency $proficiency
     */
    public function reloadEntity($proficiency);
}
