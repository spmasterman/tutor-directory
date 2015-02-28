<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\OperatingRegion;

interface OperatingRegionManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return OperatingRegion
     */
    public function findById($id);

    /**
     * @return OperatingRegion[]
     */
    public function findAll();

    /**
     * Returns all active competencyLevels as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * (there's no obvious grouping, so its a flat list for OperatingRegion)
     *
     * @return array
     */
    public function buildGroupedChoices();

    /**
     * @return null|OperatingRegion
     */
    public function findDefaultOperatingRegion();

    /**
     * @param OperatingRegion $operatingRegion
     * @param bool            $withFlush
     */
    public function saveEntity($operatingRegion, $withFlush = true);

    /**
     * Create a new OperatingRegion.
     *
     * Set its default values
     *
     * @return OperatingRegion
     */
    public function createOperatingRegion();

    /**
     * @param int $id
     */
    public function removeOperatingRegion($id);

    /**
     * @param OperatingRegion $operatingRegion
     */
    public function refreshOperatingRegion(OperatingRegion $operatingRegion);
}
