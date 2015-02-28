<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Status;

interface StatusManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Status
     */
    public function findById($id);

    /**
     * @return Status[]
     */
    public function findAll();

    /**
     * Returns all active competencyLevels as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * (there's no obvious grouping, so its a flat list for Status)
     *
     * @return array
     */
    public function buildGroupedChoices();

    /**
     * @return null|Status
     */
    public function findDefaultStatus();

    /**
     * @param Status $status
     * @param bool   $withFlush
     */
    public function saveEntity($status, $withFlush = true);

    /**
     * Create a new Status.
     *
     * Set its default values
     *
     * @return Status
     */
    public function createStatus();

    /**
     * @param int $id
     */
    public function removeStatus($id);

    /**
     * @param Status $status
     */
    public function refreshStatus(Status $status);
}
