<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\ReportRepository;
use Fitch\TutorBundle\Entity\Report;

class ReportManager extends BaseModelManager
{
    /**
     * @param $id
     * @throws EntityNotFoundException
     * @return Report
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return Report[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @param Report $report
     * @param bool $withFlush
     */
    public function saveReport($report, $withFlush = true)
    {
        parent::saveEntity($report, $withFlush);
    }

    /**
     * Create a new Report
     *
     * Set its default values
     *
     * @return Report
     */
    public function createReport()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeReport($id)
    {
        $report = $this->findById($id);
        parent::removeEntity($report);
    }

    /**
     * @param Report $report
     */
    public function refreshReport(Report $report)
    {
        parent::reloadEntity($report);
    }

    /**
     * @return ReportRepository
     */
    private function getRepo()
    {
        return $this->repo;
    }

    /**
     * Used  to identify logs generated by this class
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.report';
    }
}
