<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\TutorType;

interface TutorTypeManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return TutorType
     */
    public function findById($id);

    /**
     * @return TutorType[]
     */
    public function findAll();

    /**
     * @return null|TutorType
     */
    public function findDefaultEntity();

    /**
     * Returns all active tutorTypes as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * (there's no obvious grouping, so its a flat list for TutorType)
     *
     * @return array
     */
    public function buildGroupedChoices();

    /**
     * @param TutorType $tutorType
     * @param bool      $withFlush
     */
    public function saveEntity($tutorType, $withFlush = true);

    /**
     * Create a new TutorType.
     *
     * Set its default values
     *
     * @return TutorType
     */
    public function createEntity();

    /**
     * @param TutorType $entity
     * @param bool $withFlush
     */
    public function removeEntity($entity, $withFlush = true);

    /**
     * @param TutorType $tutorType
     */
    public function reloadEntity($tutorType);
}
