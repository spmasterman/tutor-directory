<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\CompetencyType;

interface CompetencyTypeManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return CompetencyType
     */
    public function findById($id);

    /**
     * @return CompetencyType[]
     */
    public function findAll();

    /**
     * @return CompetencyType[]
     */
    public function findAllSorted();

    /**
     * @return array
     */
    public function buildChoices();

    /**
     * Returns all active competencyTypes as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * @param CategoryManagerInterface $categoryManager
     *
     * @return array
     */
    public function buildGroupedChoices(CategoryManagerInterface $categoryManager);

    /**
     * @param string                   $competencyTypeName
     * @param CategoryManagerInterface $categoryManager
     *
     * @return CompetencyType
     */
    public function findOrCreate($competencyTypeName, CategoryManagerInterface $categoryManager);

    /**
     * @param CompetencyType $competencyType
     * @param bool           $withFlush
     */
    public function saveCompetencyType($competencyType, $withFlush = true);

    /**
     * Create a new CompetencyType.
     *
     * Set its default values
     *
     * @param CategoryManagerInterface $categoryManager
     *
     * @return CompetencyType
     */
    public function createCompetencyType(CategoryManagerInterface $categoryManager);

    /**
     * @param CompetencyType           $competencyType
     * @param CategoryManagerInterface $categoryManager
     */
    public function setDefaultCategory(CompetencyType $competencyType, CategoryManagerInterface $categoryManager);

    /**
     * @param int $id
     */
    public function removeCompetencyType($id);

    /**
     * @param CompetencyType $competencyType
     */
    public function refreshCompetencyType(CompetencyType $competencyType);
}
