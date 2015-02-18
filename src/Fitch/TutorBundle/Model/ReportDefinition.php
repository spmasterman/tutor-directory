<?php

namespace Fitch\TutorBundle\Model;

use Fitch\TutorBundle\Entity\CompetencyLevel;
use Fitch\TutorBundle\Entity\CompetencyType;
use Fitch\TutorBundle\Entity\Currency;
use Fitch\TutorBundle\Entity\Language;
use Fitch\TutorBundle\Entity\OperatingRegion;
use Fitch\TutorBundle\Entity\Report;
use Fitch\TutorBundle\Entity\Status;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Entity\TutorType;
use Fitch\UserBundle\Entity\User;
use JMS\Serializer\Annotation\Type;
use Liuggio\ExcelBundle\Factory;
use Symfony\Component\Form\FormInterface;

class ReportDefinition
{
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
        foreach($form->getData()['tutor_type'] as $tutorType) {
            /** @var TutorType $tutorType */
            $this->tutorTypeIds[] = $tutorType->getId();
        }
        foreach($form->getData()['status'] as $status) {
            /** @var Status $status */
            $this->statusIds[] = $status->getId();
        }
        foreach($form->getData()['operating_region'] as $region) {
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
            foreach($form->getData()['competency']['competencyType'] as $competencyType) {
                /** @var CompetencyType $competencyType */
                $this->competencyTypeIds[] = $competencyType->getId();
            }
        }

        if (array_key_exists('competencyLevel', $form->getData()['competency'])) {
            foreach($form->getData()['competency']['competencyLevel'] as $competencyLevel) {
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
    public function isFieldDisplayed($field) {
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
    public function getReportCurrencyToGBP() {
        if ($this->currency) {
            return  $this->currency->getToGBP();
        }
        return 1;
    }

    /**
     * @return string
     */
    public function getReportCurrencyThreeLetterCode() {
        if ($this->currency) {
            return  $this->currency->getThreeDigitCode();
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

        return '(\'' . implode('\',\'', $this->rateTypes) . '\')' ;
    }

    /**
     * @param $tutorCurrencyAlias
     * @return string
     */
    public function getRateLimitAsExpression($tutorCurrencyAlias) {
        switch ($this->operator) {
            case 'lt' : $op = ' < '; break;
            case 'lte' : $op = ' <= '; break;
            case 'eq' : $op = ' = '; break;
            case 'gte' : $op = ' >= '; break;
            case 'gt' : $op = ' > '; break;
            default: throw new \InvalidArgumentException($this->operator . ' is not a valid operator');
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
            && $this->currency
        ;
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
            $value = (int) trim($value);
        });

        return '(' . implode(',', $idArray) . ')' ;
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
     *
     * @return \PHPExcel
     * @throws \PHPExcel_Exception
     */
    public function createPHPExcelObject(Factory $excelFactory, User $user, Report $report, $data)
    {
        $phpExcel = $excelFactory->createPHPExcelObject();

        $phpExcel->getProperties()->setCreator("Fitch Learning")
            ->setLastModifiedBy($user->getFullName() . '(' . $user->getEmail() . ')')
            ->setTitle($report->getName())
            ->setSubject("Fitch Learning Trainer Report")
            ->setDescription("Report created by the Fitch Trainer system")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Result file");

        $this->populateSheet($phpExcel, 0, $data);

        $phpExcel->getActiveSheet()->setTitle('ReportData');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcel->setActiveSheetIndex(0);
        return $phpExcel;
    }

    private function populateSheet(\PHPExcel $phpExcel, $sheet, $data)
    {
        $phpExcel->setActiveSheetIndex($sheet)

//        {% if definition.isFieldDisplayed('name') %}<th>Name</th>{% endif %}
//            {% if definition.isFieldDisplayed('tutor_type') %}<th>Type</th>{% endif %}
//            {% if definition.isFieldDisplayed('status') %}<th>Status</th>{% endif %}
//            {% if definition.isFieldDisplayed('region') %}<th>Region</th>{% endif %}
//            {% if definition.isFieldDisplayed('languages') %}<th>Language(s)</th>{% endif %}
//            {% if definition.isFieldDisplayed('addresses') %}<th>Address(es)</th>{% endif %}
//            {% if definition.isFieldDisplayed('emails') %}<th>Email(s)</th>{% endif %}
//            {% if definition.isFieldDisplayed('phones') %}<th>Phone Number(s)</th>{% endif %}
//            {% if definition.isFieldDisplayed('skills') %}<th>Skills</th>{% endif %}
//            {% if unrestricted and definition.isFieldDisplayed('rates') %}<th>Rates</th>{% endif %}
//            {% if definition.isFieldDisplayed('bio') %}<th>Biography</th>{% endif %}
//            {% if definition.isFieldDisplayed('linkedin') %}<th>LinkedIn Profile</th>{% endif %}
//            {% if unrestricted and definition.isFieldDisplayed('notes') %}<th>Terms of Engagement Notes</th>{% endif %}
//            {% if definition.isFieldDisplayed('created') %}<th>Created</th>{% endif %}
//        </tr>
//        </thead>
//        <tbody>
//        {% for tutor in data %}
//            <tr>
//                {% if definition.isFieldDisplayed('name') %}<td>{{ tutor.getName }}</td>{% endif %}
//                {% if definition.isFieldDisplayed('tutor_type') %}<td>{{ tutor.getTutorType }}</td>{% endif %}
//                {% if definition.isFieldDisplayed('status') %}<td>{{ tutor.getStatus }}</td>{% endif %}
//                {% if definition.isFieldDisplayed('region') %}<td>{{ tutor.getRegion }}</td>{% endif %}
//                {% if definition.isFieldDisplayed('languages') %}
//                    <td>
//                        {% for tutorLanguage in tutor.getTutorLanguages %}
//                            {% if loop.first %}
//                                <ul class="fa-ul">
//                            {% endif %}
//                            <li><i class="fa-li fa fa-language"></i>{{ tutorLanguage.getLanguage }} {% if tutorLanguage.getNote %} - <em>{{ tutorLanguage.getNote }}</em>{% endif %}</li>
//                            {% if loop.last %}
//                                </ul>
//                            {% endif %}
//                        {% endfor %}
//                    </td>
//                {% endif %}
//                {% if definition.isFieldDisplayed('addresses') %}
//                    <td>
//                        {% for address in tutor.getAddresses %}
//                            {% if loop.first %}
//                                <ul class="fa-ul">
//                            {% endif %}
//                            <li><i class="fa-li fa fa-envelope-o"></i>{{ address }} ({{ address.getType }})</li>
//                            {% if loop.last %}
//                                </ul>
//                            {% endif %}
//                        {% endfor %}
//                    </td>
//                {% endif %}
//                {% if definition.isFieldDisplayed('emails') %}
//                    <td>
//                        {% for email in tutor.getEmailAddresses %}
//                            {% if loop.first %}
//                                <ul class="fa-ul">
//                            {% endif %}
//                            <li><i class="fa-li fa fa-at"></i>{{ email }} ({{ email.getType }})</li>
//                            {% if loop.last %}
//                                </ul>
//                            {% endif %}
//                        {% endfor %}
//                    </td>
//                {% endif %}
//                {% if definition.isFieldDisplayed('phones') %}
//                    <td>
//                        {% for phoneNumber in tutor.getPhoneNumbers %}
//                            {% if loop.first %}
//                                <ul class="fa-ul">
//                            {% endif %}
//                            <li><i class="fa-li fa fa-phone"></i>{% if phoneNumber.isPreferred %}<strong>{% endif %}{{ phoneNumber }}{% if phoneNumber.isPreferred %}</strong>{% endif %}</li>
//                            {% if loop.last %}
//                                </ul>
//                            {% endif %}
//                        {% endfor %}
//                    </td>
//                {% endif %}
//                {% if definition.isFieldDisplayed('skills') %}
//                    <td>
//                        {% for competency in tutor.getCompetencies %}
//                            {% if loop.first %}
//                                <ul class="fa-ul">
//                            {% endif %}
//                            <li><i class="fa-li fa fa-square"></i>{% if competency.getCompetencyType %}{{ competency.getCompetencyType.getName }}{% endif %}{% if competency.getCompetencyLevel %} ({{ competency.getCompetencyLevel.getName }}){% endif %}{% if competency.getNote %} - <em>{{ competency.getNote }}</em>{% endif %}</li>
//                            {% if loop.last %}
//                                </ul>
//                            {% endif %}
//                        {% endfor %}
//                    </td>
//                {% endif %}
//                {% if unrestricted %}
//                    {% if definition.isFieldDisplayed('rates') %}
//                        <td>
//                            {% for rate in tutor.getRates %}
//                                {% if loop.first %}
//                                    <ul class="fa-ul">
//                                {% endif %}
//                                <li><i class="fa-li fa fa-money"></i>{{ rate.getName }} Rate: {{ rate.getAmount|number_format(2, '.', ',') }} {{ tutor.getCurrency.getThreeDigitCode }} ({{ (rate.getAmount * (tutor.getCurrency.getToGBP / definition.getReportCurrencyToGBP))|number_format(2, '.', ',') }} {{ definition.getReportCurrencyThreeLetterCode }})</li>
//                                {% if loop.last %}
//                                    </ul>
//                                {% endif %}
//                            {% endfor %}
//                        </td>
//                    {% endif %}
//                {% endif %}
//                {% if definition.isFieldDisplayed('bio') %}<td>{{ tutor.getBio }}</td>{% endif %}
//                {% if definition.isFieldDisplayed('linkedin') %}
//                    <td>
//                        {% if tutor.getLinkedInURL %}
//                            <a href="{{ tutor.getLinkedInURL }}" target="_blank">{{ tutor.getLinkedInURL }}</a>
//                        {% endif %}
//                    </td>
//                {% endif %}
//                {% if unrestricted and definition.isFieldDisplayed('notes') %}
//                    <td>
//                        {% for note in tutor.getNotes %}
//                            {% if loop.first %}
//                                <ul class="fa-ul">
//                            {% endif %}
//                            <li><i class="fa-li fa fa-comment-o"></i>{{ note.getBody }} - <em>{{ note.getProvenance }}</em></li>
//                            {% if loop.last %}
//                                </ul>
//                            {% endif %}
//                        {% endfor %}
//                    </td>
//                {% endif %}
//                {% if definition.isFieldDisplayed('created') %}<td>{{ tutor.getCreated|localizeddate('medium','none') }}</td>{% endif %}
//            </tr>
//        {% endfor %}
//
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!');

    }
}