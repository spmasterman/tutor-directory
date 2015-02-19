<?php

namespace Fitch\TutorBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Fitch\TutorBundle\Entity\Address;
use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Entity\CompetencyLevel;
use Fitch\TutorBundle\Entity\CompetencyType;
use Fitch\TutorBundle\Entity\Currency;
use Fitch\TutorBundle\Entity\Email;
use Fitch\TutorBundle\Entity\Language;
use Fitch\TutorBundle\Entity\Note;
use Fitch\TutorBundle\Entity\OperatingRegion;
use Fitch\TutorBundle\Entity\Phone;
use Fitch\TutorBundle\Entity\Rate;
use Fitch\TutorBundle\Entity\Report;
use Fitch\TutorBundle\Entity\Status;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Entity\TutorLanguage;
use Fitch\TutorBundle\Entity\TutorType;
use Fitch\UserBundle\Entity\User;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Handler\ArrayCollectionHandler;
use Liuggio\ExcelBundle\Factory;
use PHPExcel_Cell as Cell;
use PHPExcel_Worksheet as Sheet;
use PHPExcel_Style_Alignment;
use Symfony\Component\Form\FormInterface;

class ReportDefinition
{
    const HEIGHT_PER_LINE = 14;

    /**
     * @var array
     * @Type("array<integer>")
     */
    private $tutorTypeIds = [];

    /**
     * @var array
     * @Type("array<integer>")
     */
    private $statusIds = [];

    /**
     * @var array
     * @Type("array<integer>")
     */
    private $regionIds = [];

    /**
     * @var array
     * @Type("array<integer>")
     */
    private $languageIds = [];

    /**
     * @var array
     * @Type("array<string>")
     */
    private $rateTypes = [];

    /**
     * @var string
     * @Type("string")
     */
    private $operator = '';

    /**
     * @var int
     * @Type("integer")
     */
    private $rateAmount = 0;

    /**
     * @var Currency
     * @Type("Fitch\TutorBundle\Entity\Currency")
     */
    private $currency = null;

    /**
     * @var array
     * @Type("array<integer>")
     */
    private $competencyTypeIds = [];

    /**
     * @var array
     * @Type("array<integer>")
     */
    private $competencyLevelIds = [];

    /**
     * @var array
     * @Type("array<string>")
     */
    private $fields = [];

    /**
     * Bit of a long constructor - its job is just to suck the relevant values out the form
     *
     * @param FormInterface $form
     * @param bool $unrestricted
     */
    public function __construct(FormInterface $form, $unrestricted = false)
    {
        foreach ($form->getData()['tutor_type'] as $tutorType) {
            /** @var TutorType $tutorType */
            $this->tutorTypeIds[] = $tutorType->getId();
        }
        foreach ($form->getData()['status'] as $status) {
            /** @var Status $status */
            $this->statusIds[] = $status->getId();
        }
        foreach ($form->getData()['operating_region'] as $region) {
            /** @var OperatingRegion $region */
            $this->regionIds[] = $region->getId();
        }

        /** @var Language $language */
        $language = $form->getData()['language'];
        if ($language instanceof Language) {
            $this->languageIds[] = $language->getId();
        }

        // Setup the Rate filter - only if the user has the correct rights...
        if ($unrestricted) {
            $this->rateTypes = $form->getData()['rate']['rateType'];
            $this->operator = $form->getData()['rate']['operator'];
            $this->rateAmount = $form->getData()['rate']['amount'];
            $this->currency = $form->getData()['rate']['currency'];
        }

        // Setup any selected Competency Filter
        if (array_key_exists('competencyType', $form->getData()['competency'])) {
            foreach ($form->getData()['competency']['competencyType'] as $competencyType) {
                /** @var CompetencyType $competencyType */
                $this->competencyTypeIds[] = $competencyType->getId();
            }
        }

        if (array_key_exists('competencyLevel', $form->getData()['competency'])) {
            foreach ($form->getData()['competency']['competencyLevel'] as $competencyLevel) {
                /** @var CompetencyLevel $competencyLevel */
                $this->competencyLevelIds[] = $competencyLevel->getId();
            }
        }

        // Grab the Fields out of the form...
        $this->fields = $form->getData()['fields'];
    }

    /**
     * @param string $field
     * @return bool
     */
    public function isFieldDisplayed($field)
    {
        return in_array($field, $this->fields);
    }

    /**
     * @return bool
     */
    public function isFilteredByCompetency()
    {
        return $this->isFilteredByCompetencyLevel() || $this->isFilteredByCompetencyType();
    }

    /**
     * @return bool
     */
    public function isFilteredByCompetencyType()
    {
        return (bool)count($this->competencyTypeIds);
    }

    /**
     * @return bool
     */
    public function isFilteredByCompetencyLevel()
    {
        return (bool)count($this->competencyLevelIds);
    }

    /**
     * @return string
     */
    public function getCompetencyTypeIdsAsSet()
    {
        return $this->getIDsAsSet($this->competencyTypeIds);
    }

    /**
     * @return string
     */
    public function getCompetencyLevelIdsAsSet()
    {
        return $this->getIDsAsSet($this->competencyLevelIds);
    }

    /**
     * @return float|int
     */
    public function getReportCurrencyToGBP()
    {
        if ($this->currency) {
            return $this->currency->getToGBP();
        }
        return 1;
    }

    /**
     * @return string
     */
    public function getReportCurrencyThreeLetterCode()
    {
        if ($this->currency) {
            return $this->currency->getThreeDigitCode();
        }
        return 'GBP';
    }

    /**
     * @return bool
     */
    public function isFilteredByRateType()
    {
        return (bool)count($this->rateTypes);
    }

    /**
     * @return string
     */
    public function getRateTypesAsSet()
    {
        array_walk($this->rateTypes, function (&$value) {
            $value = preg_replace("/[^[:alnum:][:space:]]/ui", '', strtolower($value));
        });

        return '(\'' . implode('\',\'', $this->rateTypes) . '\')';
    }

    /**
     * @param $tutorCurrencyAlias
     * @return string
     */
    public function getRateLimitAsExpression($tutorCurrencyAlias)
    {
        switch ($this->operator) {
            case 'lt' :
                $op = ' < ';
                break;
            case 'lte' :
                $op = ' <= ';
                break;
            case 'eq' :
                $op = ' = ';
                break;
            case 'gte' :
                $op = ' >= ';
                break;
            case 'gt' :
                $op = ' > ';
                break;
            default:
                throw new \InvalidArgumentException($this->operator . ' is not a valid operator');
        }

        return " * ({$tutorCurrencyAlias}.toGBP / {$this->currency->getToGBP()}){$op}{$this->rateAmount}";
    }

    /**
     * @return bool
     */
    public function isFilteredByRate()
    {
        // there can be no rate types BUT there must be an amount, operator and Currency
        return
            (bool)$this->operator
            && $this->rateAmount
            && $this->currency;
    }

    /**
     * @return bool
     */
    public function isFilteredByLanguage()
    {
        return (bool)count($this->languageIds);
    }

    /**
     * @return string
     */
    public function getLanguageIDsAsSet()
    {
        return $this->getIDsAsSet($this->languageIds);
    }

    /**
     * @return bool
     */
    public function isFilteredByTutorType()
    {
        return (bool)count($this->tutorTypeIds);
    }

    /**
     * @return string
     */
    public function getTutorTypeIDsAsSet()
    {
        return $this->getIDsAsSet($this->tutorTypeIds);
    }

    /**
     * @return bool
     */
    public function isFilteredByStatus()
    {
        return (bool)count($this->statusIds);
    }

    /**
     * @return string
     */
    public function getStatusIDsAsSet()
    {
        return $this->getIDsAsSet($this->statusIds);
    }

    /**
     * @return bool
     */
    public function isFilteredByRegion()
    {
        return (bool)count($this->regionIds);
    }

    /**
     * @return string
     */
    public function getRegionIDsAsSet()
    {
        return $this->getIDsAsSet($this->regionIds);
    }

    /**
     * @param array $idArray
     * @return string
     */
    private function getIDsAsSet($idArray)
    {
        array_walk($idArray, function (&$value) {
            $value = (int)trim($value);
        });

        return '(' . implode(',', $idArray) . ')';
    }

    /**
     * @return array
     */
    public static function getAvailableFields()
    {
        return [
            'name' => 'Full Name',
            'tutor_type' => 'Trainer Type',
            'status' => 'Status',
            'region' => 'Region',
            'languages' => 'Languages',
            'skills' => 'Skills',
            'rates' => 'Rates [Restricted]',
            'addresses' => 'Addresses',
            'emails' => 'Email Addresses',
            'phones' => 'Phone Numbers',
            'bio' => 'Biography',
            'linkedin' => 'LinkedIn Profile',
            'notes' => 'Terms of Engagement Notes [Restricted]',
            'created' => 'Created Date'
        ];
    }

    /**
     * @return array
     */
    public static function getDefaultFields()
    {
        return [
            'name',
            'tutor_type',
            'status',
            'region',
            'skills'
        ];
    }

    /**
     * @param Factory $excelFactory
     * @param User $user
     * @param Report $report
     * @param Tutor[] $data
     * @param bool $unrestricted
     *
     * @return \PHPExcel
     * @throws \PHPExcel_Exception
     */
    public function createPHPExcelObject(Factory $excelFactory, User $user, Report $report, $data, $unrestricted)
    {
        $phpExcel = $excelFactory->createPHPExcelObject();

        $phpExcel->getProperties()->setCreator("Fitch Learning")
            ->setLastModifiedBy($user->getFullName() . '(' . $user->getEmail() . ')')
            ->setTitle($report->getName())
            ->setSubject("Fitch Learning Trainer Report")
            ->setDescription("Report created by the Fitch Trainer system")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Result file");

        $this->populateSheet($phpExcel, 0, $report, $data, $unrestricted);

        $phpExcel->getActiveSheet()->setTitle('ReportData');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcel->setActiveSheetIndex(0);
        return $phpExcel;
    }

    /**
     * @param \PHPExcel $phpExcel
     * @param int $sheetNum
     * @param Report $report
     * @param Tutor[] $data
     * @param bool $unrestricted
     *
     * @throws \PHPExcel_Exception
     */
    private function populateSheet(\PHPExcel $phpExcel, $sheetNum, Report $report, $data, $unrestricted)
    {

        $sheet = $phpExcel->setActiveSheetIndex($sheetNum);
        $sheet->getDefaultRowDimension()->setRowHeight(18);
        $sheet->setShowGridlines(false);
        $sheet->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        // Set Reportr Title
        $sheet->setCellValue('A1', $report->getName());
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setUnderline(true);

        // Set Report Headert Row
        $row = 3;
        $col = 0;
        foreach (self::getAvailableFields() as $key => $value) {
            if ($this->isFieldDisplayed($key)) {
                $sheet->setCellValueByColumnAndRow($col++, $row, $value);
            }
        }
        $this->headerFormat($sheet, 'A' . $row . ':' . Cell::stringFromColumnIndex(--$col) . $row, 'cccccc');

        // Set Report Data
        foreach ($data as $tutor) {
            $row++;
            $col = 0;
            $maxLines = 1;

            $this->scalarCell($sheet, 'name', $col, $row, $tutor->getName(), 30);
            $this->scalarCell($sheet, 'tutor_type', $col, $row, $tutor->getTutorType()->getName(), 20);
            $this->scalarCell($sheet, 'status', $col, $row, $tutor->getStatus()->getName(), 30);
            $this->scalarCell($sheet, 'region', $col, $row, $tutor->getRegion()->getName(), 20);
            $this->arrayCell($sheet, 'languages', $col, $row, $tutor->getTutorLanguages(), 80, $maxLines, $this->getLanguageCellFormatter());
            $this->arrayCell($sheet, 'skills', $col, $row, $tutor->getCompetencies(), 40, $maxLines, $this->getCompetencyCellFormatter());
            $this->arrayCell($sheet, 'rates', $col, $row, $tutor->getRates(), 40, $maxLines, $this->getRateCellFormatter($unrestricted, $tutor));
            $this->arrayCell($sheet, 'addresses', $col, $row, $tutor->getAddresses(), 100, $maxLines, $this->getAddressCellFormatter());
            $this->arrayCell($sheet, 'emails', $col, $row, $tutor->getEmailAddresses(), 40, $maxLines, $this->getEmailCellFormatter());
            $this->arrayCell($sheet, 'phones', $col, $row, $tutor->getPhoneNumbers(), 40, $maxLines, $this->getPhoneCellFormatter());
            $this->scalarCell($sheet, 'bio', $col, $row, $tutor->getBio(), 80);
            $sheet->getStyleByColumnAndRow($col-1, $row)->getAlignment()->setWrapText(true);
            $this->scalarCell($sheet, 'linkedin', $col, $row, $tutor->getLinkedInURL(), 50);
            $sheet->getStyleByColumnAndRow($col, $row)->getAlignment()->setWrapText(true);
            $this->arrayCell($sheet, 'notes', $col, $row, $tutor->getNotes(), 100, $maxLines, $this->getNoteCellFormatter($unrestricted));
            $this->scalarCell($sheet, 'created', $col, $row, $tutor->getCreated()->format('Y-m-d'), 15);

            // Set the Row Height, based on maxLines (which is the number of lines of text in the most populated cell)
            $sheet->getRowDimension($row)->setRowHeight(self::HEIGHT_PER_LINE * $maxLines);
        }
        // vertical align top the whole report
        $sheet->getStyle('A1:' . Cell::stringFromColumnIndex($col) . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
    }

    /**
     * @param $unrestricted
     * @return callable
     */
    private function getNoteCellFormatter($unrestricted)
    {
        if ($unrestricted) {
            return function (Note $note) {
                return $note->getBody() . ' - ' . $note->getProvenance();
            };
        }
        return function () {
            return "You do not have sufficient privileges.";
        };
    }

    /**
     * @param $unrestricted
     * @param Tutor $tutor
     * @return callable
     */
    private function getRateCellFormatter($unrestricted, Tutor $tutor)
    {
        $self = $this;
        if ($unrestricted) {
            return  $formatter = function (Rate $rate) use ($tutor, $self) {
                return $rate->getName()
                . ' Rate:'
                . number_format($rate->getAmount(), 2)
                . ' '
                . $tutor->getCurrency()->getThreeDigitCode()
                . ' ('
                . number_format($rate->getAmount() * $tutor->getCurrency()->getToGBP() / $self->getReportCurrencyToGBP(), 2)
                . ' '
                . $self->getReportCurrencyThreeLetterCode()
                . ')';
            };
        }
        return function () {
            return "You do not have sufficient privileges.";
        };
    }

    /**
     * @return callable
     */
    private function getAddressCellFormatter() {
        return  function (Address $address) {
            return "{$address->__toString()} ({$address->getType()})";
        };
    }

    /**
     * @return callable
     */
    private function getEmailCellFormatter() {
        return function (Email $email) {
            return "{$email->__toString()} ({$email->getType()})";
        };
    }

    /**
     * @return callable
     */
    private function getPhoneCellFormatter() {
        return function (Phone $phone) {
            return
                $phone->__toString() .
                ($phone->isPreferred()
                    ? ' - Preferred'
                    : '');
        };
    }

    /**
     * @return callable
     */
    private function getCompetencyCellFormatter() {
        return function (Competency $competency) {
            return
                ($competency->getCompetencyType()
                    ? $competency->getCompetencyType()->getName()
                    : '') .
                ($competency->getCompetencyLevel()
                    ? '(' . $competency->getCompetencyLevel()->getName() . ') '
                    : '');
        };
    }

    /**
     * @return callable
     */
    private function getLanguageCellFormatter() {
        return function (TutorLanguage $tutorLanguage) {
            return
                $tutorLanguage->getLanguage()->getName() .
                ($tutorLanguage->getNote()
                    ? ' - ' . $tutorLanguage->getNote()
                    : '');
        };
    }

    /**
     * @param Sheet $sheet
     * @param string $fieldName
     * @param int $col
     * @param int $row
     * @param string $value
     * @param int $width
     */
    private function scalarCell(Sheet $sheet, $fieldName, &$col, $row, $value, $width)
    {
        if ($this->isFieldDisplayed($fieldName)) {
            $sheet->setCellValueByColumnAndRow($col, $row, $value);
            $sheet->getColumnDimensionByColumn($col)->setWidth($width);
            $col++;
        }
    }

    /**
     * @param Sheet $sheet
     * @param string $fieldName
     * @param int $col
     * @param int $row
     * @param ArrayCollection $value
     * @param int $width
     * @param int $maxLines
     * @param callable $fn
     */
    private function arrayCell(Sheet $sheet, $fieldName, &$col, $row, $value, $width, &$maxLines, $fn)
    {
        if ($this->isFieldDisplayed($fieldName)) {
            $maxLines = max($maxLines, $value->count());
            $sheet->setCellValueByColumnAndRow(
                $col,
                $row,
                implode("\n", $value->map($fn)->toArray()
                )
            );
            $sheet->getColumnDimensionByColumn($col)->setWidth($width);
            $col++;
        }
    }

    /**
     * @param Sheet $sheet
     * @param $cells
     * @param $color
     * @throws \PHPExcel_Exception
     */
    private function headerFormat(Sheet $sheet, $cells, $color){
        $style = $sheet->getStyle($cells);
        $style->getFill()->applyFromArray([
            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => [
                'rgb' => $color
            ]
        ]);
        $style->getFont()->setBold(true);
    }
}




