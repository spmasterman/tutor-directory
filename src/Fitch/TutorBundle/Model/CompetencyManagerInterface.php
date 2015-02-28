<?php

namespace Fitch\TutorBundle\Model;

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
    public function saveEntity($competency, $withFlush = true);

    /**
     * Create a new Competency.
     *
     * Set its default values
     *
     * @return Competency
     */
    public function createEntity();

    /**
     * @param int $id
     */
    public function removeEntity($id);

    /**
     * @param Competency $competency
     */
    public function reloadEntity($competency);
}
