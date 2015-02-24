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

    /** @var  ReportDefinition */
    private $definition;

    /** @var  \PHPExcel */
    private $phpExcel;

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
        $sheet = $this->phpExcel->setActiveSheetIndex($sheetNum);
        $sheet->getDefaultRowDimension()->setRowHeight(18);
        $sheet->setShowGridlines(false);
        $sheet->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        // Set Report Title
        $sheet->setCellValue('A1', $report->getName());
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setUnderline(true);

        // Set Report Header Row
        $row = 3;
        $col = 0;
        foreach (ReportDefinition::getAvailableFields() as $key => $value) {
            if ($this->definition->isFieldDisplayed($key)) {
                $sheet->setCellValueByColumnAndRow($col++, $row, $value);
            }
        }
        $this->headerFormat($sheet, 'A'.$row.':'.Cell::stringFromColumnIndex(--$col).$row, 'cccccc');

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
        $sheet->getStyle('A1:'.Cell::stringFromColumnIndex($col).$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
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
                .number_format($rate->getAmount() * $tutor->getCurrency()->getToGBP() / $definition->getReportCurrencyToGBP(), 2)
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
     * @param Sheet  $sheet
     * @param string $fieldName
     * @param int    $col
     * @param int    $row
     * @param string $value
     * @param int    $width
     */
    private function scalarCell(Sheet $sheet, $fieldName, &$col, $row, $value, $width)
    {
        if ($this->definition->isFieldDisplayed($fieldName)) {
            $sheet->setCellValueByColumnAndRow($col, $row, $value);
            $sheet->getColumnDimensionByColumn($col)->setWidth($width);
            $col++;
        }
    }

    /**
     * @param Sheet           $sheet
     * @param string          $fieldName
     * @param int             $col
     * @param int             $row
     * @param ArrayCollection $value
     * @param int             $width
     * @param int             $maxLines
     * @param callable        $formatterFunction
     */
    private function arrayCell(Sheet $sheet, $fieldName, &$col, $row, $value, $width, &$maxLines, $formatterFunction)
    {
        if ($this->definition->isFieldDisplayed($fieldName)) {
            $maxLines = max($maxLines, $value->count());
            $sheet->setCellValueByColumnAndRow(
                $col,
                $row,
                implode("\n", $value->map($formatterFunction)->toArray()
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
     *
     * @throws \PHPExcel_Exception
     */
    private function headerFormat(Sheet $sheet, $cells, $color)
    {
        $style = $sheet->getStyle($cells);
        $style->getFill()->applyFromArray([
            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => [
                'rgb' => $color,
            ],
        ]);
        $style->getFont()->setBold(true);
    }
}
