<?php

namespace Fitch\TutorBundle\Model;

use Fitch\TutorBundle\Entity\Currency;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Form\FormInterface;

class ReportDefinition extends AbstractReportDefinition
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
     * @var string
     * @Type("string")
     */
    private $languageOperator = 'and';

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
    private $categoryIds = [];

    /**
     * @var string
     * @Type("string")
     */
    private $categoryOperator = 'and';

    /**
     * @var array
     * @Type("array<integer>")
     */
    private $competencyTypeIds = [];

    /**
     * @var string
     * @Type("string")
     */
    private $competencyTypeOperator = 'and';

    /**
     * @var array
     * @Type("array<integer>")
     */
    private $competencyLevelIds = [];

    /**
     * Bit of a long constructor - its job is just to suck the relevant values out the form.
     *
     * @param FormInterface $form
     * @param bool          $unrestricted
     */
    public function __construct(FormInterface $form, $unrestricted = false)
    {
        parent::__construct($form);

        $this->extractIds($form, 'tutor_type', $this->tutorTypeIds);
        $this->extractIds($form, 'status', $this->statusIds);
        $this->extractIds($form, 'operating_region', $this->regionIds);
        $this->extractNestedIds($form, 'language', 'language', $this->languageIds);
        $this->extractNested($form, 'language', 'combine', $this->languageOperator);

        // Setup the Rate filter - only if the user has the correct rights...
        if ($unrestricted) {
            $this->extractNested($form, 'rate', 'rateType', $this->rateTypes);
            $this->extractNested($form, 'rate', 'operator', $this->operator);
            $this->extractNested($form, 'rate', 'amount', $this->rateAmount);
            $this->extractNested($form, 'rate', 'currency', $this->currency);
        }

        $this->extractNestedIds($form, 'category', 'category', $this->categoryIds);
        $this->extractNested($form, 'category', 'combine', $this->categoryOperator);
        $this->extractNestedIds($form, 'competency', 'competencyType', $this->competencyTypeIds);
        $this->extractNested($form, 'competency', 'combine', $this->competencyTypeOperator);
        $this->extractIds($form, 'competencyLevel', $this->competencyLevelIds);
    }

    /**
     * @return array
     */
    public function getTutorTypeIds()
    {
        return $this->tutorTypeIds;
    }

    /**
     * @return array
     */
    public function getStatusIds()
    {
        return $this->statusIds;
    }

    /**
     * @return array
     */
    public function getRegionIds()
    {
        return $this->regionIds;
    }

    /**
     * @return array
     */
    public function getLanguageIds()
    {
        return $this->languageIds;
    }

    /**
     * @return string
     */
    public function getLanguageOperator()
    {
        return $this->languageOperator;
    }

    /**
     * @return array
     */
    public function getCategoryIds()
    {
        return $this->categoryIds;
    }

    /**
     * @return string
     */
    public function getCategoryOperator()
    {
        return $this->categoryOperator;
    }

    /**
     * @return array
     */
    public function getCompetencyTypeIds()
    {
        return $this->competencyTypeIds;
    }

    /**
     * @return string
     */
    public function getCompetencyTypeOperator()
    {
        return $this->competencyTypeOperator;
    }

    /**
     * @return array
     */
    public function getCompetencyLevelIds()
    {
        return $this->competencyLevelIds;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function isFilteredBy($name)
    {
        switch ($name) {
            case 'Competency':
                return (bool) (count($this->competencyLevelIds) || count($this->competencyTypeIds) || count($this->categoryIds));
            case 'Category':
                return (bool) count($this->categoryIds);
            case 'CompetencyType':
                return (bool) count($this->competencyTypeIds);
            case 'CompetencyLevel':
                return (bool) count($this->competencyLevelIds);
            case 'RateType':
                return (bool) count($this->rateTypes);
            case 'Rate':
                return (bool) ($this->operator && $this->rateAmount && $this->currency);
            case 'Language':
                $languageCount = count($this->languageIds);
                if ($languageCount < 1) {
                    return false;
                }
                if ($languageCount == 1) {
                    return true;
                }

                return (bool) $this->languageOperator;
            case 'TutorType':
                return (bool) count($this->tutorTypeIds);
            case 'Status':
                return (bool) count($this->statusIds);
            case 'Region':
                return (bool) count($this->regionIds);
            default:
                return false;
        }
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
     * @return string
     */
    public function getRateTypesAsSet()
    {
        array_walk($this->rateTypes, function (&$value) {
            $value = preg_replace("/[^[:alnum:][:space:]]/ui", '', strtolower($value));
        });

        return '(\''.implode('\',\'', $this->rateTypes).'\')';
    }

    /**
     * @param $tutorCurrencyAlias
     *
     * @return string
     */
    public function getRateLimitAsExpression($tutorCurrencyAlias)
    {
        switch ($this->operator) {
            case 'lt':
                $operator = ' < ';
                break;
            case 'lte':
                $operator = ' <= ';
                break;
            case 'eq':
                $operator = ' = ';
                break;
            case 'gte':
                $operator = ' >= ';
                break;
            case 'gt':
                $operator = ' > ';
                break;
            default:
                throw new \InvalidArgumentException($this->operator.' is not a valid operator');
        }

        return " * ({$tutorCurrencyAlias}.toGBP / {$this->currency->getToGBP()}){$operator}{$this->rateAmount}";
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
            'created' => 'Created Date',
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
            'skills',
        ];
    }
}
