<?php

namespace Fitch\TutorBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Fitch\TutorBundle\Entity\Address;
use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Entity\Email;
use Fitch\TutorBundle\Entity\Note;
use Fitch\TutorBundle\Entity\Phone;
use Fitch\TutorBundle\Entity\Rate;
use Fitch\TutorBundle\Entity\Report;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Entity\TutorLanguage;
use Fitch\UserBundle\Entity\User;
use Liuggio\ExcelBundle\Factory;
use PHPExcel_Cell as Cell;
use PHPExcel_Style_Alignment;
use PHPExcel_Worksheet as Sheet;

class ReportDownloadFormatter
{
    const HEIGHT_PER_LINE = 14;

    /** @var ReportDefinition */
    private $definition;

    /** @var \PHPExcel */
    private $phpExcel;

    /** @var Sheet */
    private $currentSheet;

    /** @var int */
    private $workingRow;

    /** @var int */
    private $workingColumn;

    /** @var int */
    private $workingMaxLines;

    /**
     * @param ReportDefinitionInterface $definition
     * @param Factory                   $excelFactory
     * @param User                      $user
     * @param Report                    $report
     * @param $data
     * @param $unrestricted
     *
     * @throws \PHPExcel_Exception
     */
    public function __construct(
        ReportDefinitionInterface $definition,
        Factory $excelFactory,
        User $user,
        Report $report,
        $data,
        $unrestricted
    ) {
        $this->definition = $definition;

        $this->phpExcel = $excelFactory->createPHPExcelObject();

        $this->phpExcel->getProperties()->setCreator("Fitch Learning")
            ->setLastModifiedBy($user->getFullName().'('.$user->getEmail().')')
            ->setTitle($report->getName())
            ->setSubject("Fitch Learning Trainer Report")
            ->setDescription("Report created by the Fitch Trainer system")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Result file");

        $this->populateSheet(0, $report, $data, $unrestricted);

        $this->phpExcel->getActiveSheet()->setTitle('ReportData');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->phpExcel->setActiveSheetIndex(0);
    }

    /**
     * @return \PHPExcel
     */
    public function getPHPExcel()
    {
        return $this->phpExcel;
    }

    /**
     * @param int     $sheetNum
     * @param Report  $report
     * @param Tutor[] $data
     * @param bool    $unrestricted
     *
     * @throws \PHPExcel_Exception
     */
    private function populateSheet($sheetNum, Report $report, $data, $unrestricted)
    {
        $this->currentSheet = $this->phpExcel->setActiveSheetIndex($sheetNum);
        $this->currentSheet->getDefaultRowDimension()->setRowHeight(18);
        $this->currentSheet->setShowGridlines(false);
        $this->currentSheet->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->currentSheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $this->currentSheet->getPageSetup()->setFitToPage(true);
        $this->currentSheet->getPageSetup()->setFitToWidth(1);
        $this->currentSheet->getPageSetup()->setFitToHeight(0);

        // Set Report Title
        $this->currentSheet->setCellValue('A1', $report->getName());
        $this->currentSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setUnderline(true);

        // Set Report Header Row
        $this->workingRow = 3;
        $col = 0;
        foreach (ReportDefinition::getAvailableFields() as $key => $value) {
            if ($this->definition->isFieldDisplayed($key)) {
                $this->currentSheet->setCellValueByColumnAndRow($col++, $this->workingRow, $value);
            }
        }
        $this->headerFormat(
            'A'.$this->workingRow.':'.Cell::stringFromColumnIndex(--$this->workingColumn).$this->workingRow,
            'cccccc'
        );

        // Set Report Data
        foreach ($data as $tutor) {
            $this->workingRow++;
            $this->workingColumn = 0;
            $this->workingMaxLines = 1;

            $this->scalarCell('name', $tutor->getName(), 30);
            $this->scalarCell('tutor_type', $tutor->getTutorType()->getName(), 20);
            $this->scalarCell('status', $tutor->getStatus()->getName(), 30);
            $this->scalarCell('region', $tutor->getRegion()->getName(), 20);
            $this->arrayCell('languages', $tutor->getTutorLanguages(), 80, $this->getLanguageCellFormatter());
            $this->arrayCell('skills', $tutor->getCompetencies(), 40, $this->getCompetencyCellFormatter());
            $this->arrayCell('rates', $tutor->getRates(), 40, $this->getRateCellFormatter($unrestricted, $tutor));
            $this->arrayCell('addresses', $tutor->getAddresses(), 100, $this->getAddressCellFormatter());
            $this->arrayCell('emails', $tutor->getEmailAddresses(), 40, $this->getEmailCellFormatter());
            $this->arrayCell('phones', $tutor->getPhoneNumbers(), 40, $this->getPhoneCellFormatter());
            $this->scalarCell('bio', $tutor->getBio(), 80);
            $this->currentSheet
                ->getStyleByColumnAndRow($this->workingColumn-1, $this->workingColumn)
                ->getAlignment()
                ->setWrapText(true)
            ;
            $this->scalarCell('linkedin', $tutor->getLinkedInURL(), 50);
            $this->currentSheet
                ->getStyleByColumnAndRow($this->workingColumn, $this->workingColumn)
                ->getAlignment()
                ->setWrapText(true)
            ;
            $this->arrayCell('notes', $tutor->getNotes(), 100, $this->getNoteCellFormatter($unrestricted));
            $this->scalarCell('created', $tutor->getCreated()->format('Y-m-d'), 15);

            // Set the Row Height, based on maxLines (which is the number of lines of text in the most populated cell)
            $this->currentSheet
                ->getRowDimension($this->workingRow)
                ->setRowHeight(self::HEIGHT_PER_LINE * $this->workingMaxLines)
            ;
        }
        // vertical align top the whole report
        $this->currentSheet
            ->getStyle('A1:'.Cell::stringFromColumnIndex($this->workingColumn).$this->workingRow)
            ->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)
        ;
    }

    /**
     * @param $unrestricted
     *
     * @return callable
     */
    private function getNoteCellFormatter($unrestricted)
    {
        if ($unrestricted) {
            return function (Note $note) {
                return $note->getBody().' - '.$note->getProvenance();
            };
        }

        return function () {
            return "You do not have sufficient privileges.";
        };
    }

    /**
     * @param $unrestricted
     * @param Tutor $tutor
     *
     * @return callable
     */
    private function getRateCellFormatter($unrestricted, Tutor $tutor)
    {
        $definition = $this->definition;
        if ($unrestricted) {
            return function (Rate $rate) use ($tutor, $definition) {
                return $rate->getName()
                .' Rate:'
                .number_format($rate->getAmount(), 2)
                .' '
                .$tutor->getCurrency()->getThreeDigitCode()
                .' ('
                .number_format(
                    $rate->getAmount() * $tutor->getCurrency()->getToGBP() / $definition->getReportCurrencyToGBP(),
                    2
                )
                .' '
                .$definition->getReportCurrencyThreeLetterCode()
                .')';
            };
        }

        return function () {
            return "You do not have sufficient privileges.";
        };
    }

    /**
     * @return callable
     */
    private function getAddressCellFormatter()
    {
        return  function (Address $address) {
            return "{$address->__toString()} ({$address->getType()})";
        };
    }

    /**
     * @return callable
     */
    private function getEmailCellFormatter()
    {
        return function (Email $email) {
            return "{$email->__toString()} ({$email->getType()})";
        };
    }

    /**
     * @return callable
     */
    private function getPhoneCellFormatter()
    {
        return function (Phone $phone) {
            return
                $phone->__toString().
                ($phone->isPreferred()
                    ? ' - Preferred'
                    : '');
        };
    }

    /**
     * @return callable
     */
    private function getCompetencyCellFormatter()
    {
        return function (Competency $competency) {
            return
                ($competency->getCompetencyType()
                    ? $competency->getCompetencyType()->getName()
                    : '').
                ($competency->getCompetencyLevel()
                    ? '('.$competency->getCompetencyLevel()->getName().') '
                    : '');
        };
    }

    /**
     * @return callable
     */
    private function getLanguageCellFormatter()
    {
        return function (TutorLanguage $tutorLanguage) {
            return
                $tutorLanguage->getLanguage()->getName().
                ($tutorLanguage->getNote()
                    ? ' - '.$tutorLanguage->getNote()
                    : '');
        };
    }

    /**
     * @param string $fieldName
     * @param string $value
     * @param int    $width
     */
    private function scalarCell($fieldName, $value, $width)
    {
        if ($this->definition->isFieldDisplayed($fieldName)) {
            $this->currentSheet->setCellValueByColumnAndRow($this->workingColumn, $this->workingRow, $value);
            $this->currentSheet->getColumnDimensionByColumn($this->workingColumn)->setWidth($width);
            $this->workingColumn++;
        }
    }

    /**
     * @param string          $fieldName
     * @param ArrayCollection $value
     * @param int             $width
     * @param callable        $formatterFunction
     */
    private function arrayCell($fieldName, $value, $width, $formatterFunction)
    {
        if ($this->definition->isFieldDisplayed($fieldName)) {
            $this->workingMaxLines = max($this->workingMaxLines, $value->count());
            $this->currentSheet->setCellValueByColumnAndRow(
                $this->workingColumn,
                $this->workingRow,
                implode("\n", $value->map($formatterFunction)->toArray())
            );
            $this->currentSheet->getColumnDimensionByColumn($this->workingColumn)->setWidth($width);
            $this->workingColumn++;
        }
    }

    /**
     * @param $cells
     * @param $color
     *
     * @throws \PHPExcel_Exception
     */
    private function headerFormat($cells, $color)
    {
        $style = $this->currentSheet->getStyle($cells);
        $style->getFill()->applyFromArray([
            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => [
                'rgb' => $color,
            ],
        ]);
        $style->getFont()->setBold(true);
    }
}
