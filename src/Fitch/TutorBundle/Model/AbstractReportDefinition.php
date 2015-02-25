<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\TutorBundle\Entity\Report;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\Interfaces\ReportDefinitionInterface;
use Fitch\UserBundle\Entity\User;
use JMS\Serializer\Annotation\Type;
use Liuggio\ExcelBundle\Factory;
use Symfony\Component\Form\FormInterface;

abstract class AbstractReportDefinition implements ReportDefinitionInterface
{
    /**
     * @var array
     * @Type("array<string>")
     */
    protected $fields = [];

    /**
     * Bit of a long constructor - its job is just to suck the relevant values out the form.
     *
     * @param FormInterface $form
     */
    public function __construct(FormInterface $form)
    {
        // Grab the Fields out of the form...
        $this->fields = $form->getData()['fields'];
    }

    /**
     * @param FormInterface $form
     * @param $key
     * @param $target
     */
    protected function extractIds(FormInterface $form, $key, &$target)
    {
        $data = $form->getData();
        if (array_key_exists($key, $data)) {
            foreach ($data[$key] as $entity) {
                /* @var IdentityTraitInterface $entity */
                $target[] = $entity->getId();
            }
        }
    }

    /**
     * @param FormInterface $form
     * @param string        $key
     * @param string        $keyInner
     * @param array         $target
     */
    protected function extractNestedIds(FormInterface $form, $key, $keyInner, &$target)
    {
        $data = $form->getData();
        if (array_key_exists($key, $data)) {
            if (array_key_exists($keyInner, $data[$key])) {
                foreach ($data[$key][$keyInner] as $entity) {
                    /* @var IdentityTraitInterface $entity */
                    $target[] = $entity->getId();
                }
            }
        }
    }

    /**
     * @param FormInterface $form
     * @param string        $key
     * @param string        $keyInner
     * @param $target
     */
    protected function extractNested(FormInterface $form, $key, $keyInner, &$target)
    {
        $data = $form->getData();
        if (array_key_exists($key, $data)) {
            if (array_key_exists($keyInner, $data[$key])) {
                $target = $data[$key][$keyInner];
            }
        }
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function isFieldDisplayed($field)
    {
        return in_array($field, $this->fields);
    }

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
    public function createPHPExcelObject(Factory $excelFactory, User $user, Report $report, $data, $unrestricted)
    {
        $formatter = new ReportDownloadFormatter($this, $excelFactory, $user, $report, $data, $unrestricted);

        return $formatter->getPHPExcel();
    }
}
