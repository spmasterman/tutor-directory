<?php

namespace Fitch\TutorBundle\Model;

use Fitch\TutorBundle\Entity\Currency;
use Fitch\TutorBundle\Entity\Language;
use Fitch\TutorBundle\Entity\OperatingRegion;
use Fitch\TutorBundle\Entity\Status;
use Fitch\TutorBundle\Entity\TutorType;
use Symfony\Component\Form\FormInterface;

class ReportDefinition 
{
    /** @var array */
    private $tutorTypeIds = [];

    /** @var array */
    private $statusIds = [];

    /** @var array */
    private $regionIds = [];

    /** @var array */
    private $languageIds = [];

    /** @var array */
    private $rateTypes = [];

    /** @var string */
    private $operator = '';

    /** @var int */
    private $rateAmount = 0;

    /** @var Currency */
    private $currency = null;

    /**
     * @param FormInterface $form
     */
    public function __construct(FormInterface $form)
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

        $this->rateTypes = $form->getData()['rate']['rateType'];
        $this->operator = $form->getData()['rate']['operator'];
        $this->rateAmount = $form->getData()['rate']['amount'];
        $this->currency = $form->getData()['rate']['currency'];


        // etc...


    }

    public function isFilteredByRateType()
    {
        return (bool)count($this->rateTypes);
    }

    public function getRateTypesAsSet()
    {
        array_walk($this->rateTypes, function (&$value) {
            $value = preg_replace("/[^[:alnum:][:space:]]/ui", '', strtolower($value));
        });

        return '(\'' . implode('\',\'', $this->rateTypes) . '\')' ;
    }

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

    public static function getAvailableFields()
    {
        return [
            'name' => 'Full Name',
            'tutor_type' => 'Trainer Type',
            'status' => 'Status',
            'region' => 'Region',
            'languages' => 'Languages',
            'skills' => 'Skills',
            'rates' => 'Rates',
            'addresses' => 'Addresses',
            'emails' => 'Email Addresses',
            'phones' => 'Phone Numbers',
        ];
    }

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

} 