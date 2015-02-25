<?php

namespace Fitch\TutorBundle\Model\Interfaces;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Entity\Tutor;

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
     * @param $id
     * @param Tutor $tutor
     *
     * @return Competency
     */
    public function findOrCreateCompetency($id, Tutor $tutor);

    /**
     * @param Competency $competency
     * @param bool       $withFlush
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
