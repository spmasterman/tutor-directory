<?php

namespace Fitch\TutorBundle\Model;

use Fitch\TutorBundle\Entity\Report;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\UserBundle\Entity\User;
use Liuggio\ExcelBundle\Factory;

interface ReportDefinitionInterface
{
    /**
     * @param string $field
     *
     * @return bool
     */
    public function isFieldDisplayed($field);

    /**
     * @return array
     */
    public static function getAvailableFields();

    /**
     * @return array
     */
    public static function getDefaultFields();

    /**
     * @param Factory $excelFactory
     * @param User    $user
     * @param Report  $report
     * @param Tutor[] $data
     * @param bool    $unrestricted
     *
     * @return \PHPExcel
     *
     * @throws \PHPExcel_Exception
     */
    public function createPHPExcelObject(Factory $excelFactory, User $user, Report $report, $data, $unrestricted);
}
