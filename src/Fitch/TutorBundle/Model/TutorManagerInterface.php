<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Tutor;

interface TutorManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Tutor
     */
    public function findById($id);

    /**
     * @return Tutor[]
     */
    public function findAll();

    /**
     * @return array
     */
    public function populateTable();

    /**
     * @param ReportDefinition $definition
     *
     * @return Tutor[]
     */
    public function getReportData(ReportDefinition $definition);

    /**
     * @param Tutor $tutor
     * @param bool  $withFlush
     */
    public function saveEntity($tutor, $withFlush = true);

    /**
     * Create a new Tutor.
     *
     * Set its default values
     *
     * @return Tutor
     */
    public function createEntity();

    /**
     * @param Tutor $entity
     * @param bool $withFlush
     */
    public function removeEntity($entity, $withFlush = true);

    /**
     * @param Tutor $tutor
     */
    public function reloadEntity($tutor);
}
