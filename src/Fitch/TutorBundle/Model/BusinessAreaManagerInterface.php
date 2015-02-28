<?php
/**
 * Created by PhpStorm.
 * User: smasterman
 * Date: 28/02/15
 * Time: 15:44.
 */

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\BusinessArea;

interface BusinessAreaManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return BusinessArea
     */
    public function findById($id);

    /**
     * @return BusinessArea[]
     */
    public function findAll();

    /**
     * @return array
     */
    public function buildChoices();

    /**
     * Returns all active categories as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * @return array
     */
    public function buildGroupedChoices();

    /**
     * @return null|BusinessArea
     */
    public function findDefaultBusinessArea();

    /**
     * @param BusinessArea $businessArea
     * @param bool         $withFlush
     */
    public function saveEntity($businessArea, $withFlush = true);

    /**
     * Create a new BusinessArea.
     *
     * Set its default values
     *
     * @return BusinessArea
     */
    public function createBusinessArea();

    /**
     * @param int $id
     */
    public function removeBusinessArea($id);

    /**
     * @param BusinessArea $businessArea
     */
    public function refreshBusinessArea(BusinessArea $businessArea);
}
