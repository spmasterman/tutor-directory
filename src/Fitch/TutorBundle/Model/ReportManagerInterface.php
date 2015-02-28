<?php
/**
 * Created by PhpStorm.
 * User: smasterman
 * Date: 28/02/15
 * Time: 15:36.
 */

namespace Fitch\TutorBundle\Model;

use Doctrine\ORM\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Report;

interface ReportManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Report
     */
    public function findById($id);

    /**
     * @return Report[]
     */
    public function findAll();

    /**
     * @param Report $report
     * @param bool   $withFlush
     */
    public function saveEntity($report, $withFlush = true);

    /**
     * Create a new Report.
     *
     * Set its default values
     *
     * @return Report
     */
    public function createEntity();

    /**
     * @param int $id
     */
    public function removeEntity($id);

    /**
     * @param Report $report
     */
    public function reloadEntity($report);
}
