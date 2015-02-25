<?php

namespace Fitch\TutorBundle\Model\Interfaces;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Competency;

interface CompetencyManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Competency
     */
    public function findById($id);

    /**
     * @return Competency[]
     */
    public function findAll();

    /**
     * @param Competency $competency
     * @param bool $withFlush
     */
    public function saveCompetency($competency, $withFlush = true);

    /**
     * Create a new Competency.
     *
     * Set its default values
     *
     * @return Competency
     */
    public function createCompetency();

    /**
     * @param int $id
     */
    public function removeCompetency($id);

    /**
     * @param Competency $competency
     */
    public function refreshCompetency(Competency $competency);
}