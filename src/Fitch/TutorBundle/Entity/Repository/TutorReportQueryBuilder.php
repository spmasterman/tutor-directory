<?php

namespace Fitch\TutorBundle\Entity\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Fitch\TutorBundle\Model\ReportDefinition;

class TutorReportQueryBuilder extends QueryBuilder
{
    /** @var ReportDefinition */
    protected $definition;

    public function __construct(EntityManager $em, $entityName, $alias)
    {
        parent::__construct($em);
        $this
            ->select($alias)
            ->from($entityName, $alias)
            ->where('1 = 1')
        ;
    }

    public function applyDefinition(ReportDefinition $definition)
    {
        $this->definition = $definition;

        $this->handleRegionFilter();
        $this->handleStatusFilter();
        $this->handleTutorTypeFilter();
        $this->handleLanguageFilter();
        $this->handleRateFilter();
        $this->handleCompetencyFilter();

        return $this;
    }

    private function handleRegionFilter()
    {
        if ($this->definition->isFilteredBy('Region')) {
            $this
                ->leftJoin('t.region', 'r')
                ->andWhere('r.id IN '.$this->getIdsAsSet($this->definition->getRegionIds()))
            ;
        }
    }

    private function handleStatusFilter()
    {
        if ($this->definition->isFilteredBy('Status')) {
            $this
                ->leftJoin('t.status', 's')
                ->andWhere('s.id IN '.$this->getIdsAsSet($this->definition->getStatusIds()))
            ;
        }
    }

    private function handleTutorTypeFilter()
    {
        if ($this->definition->isFilteredBy('TutorType')) {
            $this
                ->leftJoin('t.tutorType', 'tt')
                ->andWhere('tt.id IN '.$this->getIdsAsSet($this->definition->getTutorTypeIds()))
            ;
        }
    }

    private function handleLanguageFilter()
    {
        if ($this->definition->isFilteredBy('Language')) {
            $this
                ->leftJoin('t.tutorLanguages', 'tl')
                ->leftJoin('tl.language', 'l')
                ->andWhere('l.id IN '.$this->getIdsAsSet($this->definition->getLanguageIds()))
            ;

            if ($this->definition->getLanguageOperator() == 'and') {
                $this
                    ->groupBy('t.id')
                    ->having('COUNT(DISTINCT l.id) = '.count($this->definition->getLanguageIds()))
                ;
            }
        }
    }

    private function handleRateFilter()
    {
        if ($this->definition->isFilteredBy('Rate')) {
            $this
                ->join('t.rates', 'ra')
                ->join('t.currency', 'c')
                ->andWhere('ra.amount '.$this->definition->getRateLimitAsExpression('c'))
            ;
            if ($this->definition->isFilteredBy('RateType')) {
                $this->andWhere('LOWER(ra.name) IN '.$this->definition->getRateTypesAsSet());
            }
        }
    }

    private function handleCompetencyFilter()
    {
        if ($this->definition->isFilteredBy('Competency')) {
            $this
                ->join('t.competencies', 'tc')
                ->join('tc.competencyType', 'tct')
                ->join('tct.category', 'cat')
            ;

            if ($this->definition->isFilteredBy('Category')) {
                $this->handleCategoryFilter();
            } elseif ($this->definition->isFilteredBy('CompetencyType')) {
                $this->handleCompetencyTypeFilter();
            }

            if ($this->definition->isFilteredBy('CompetencyLevel')) {
                $this
                    ->join('tc.competencyLevel', 'tcl')
                    ->andWhere('tcl.id IN '.$this->getIdsAsSet($this->definition->getCompetencyLevelIds()))
                ;
            }
        }
    }

    private function handleCategoryFilter()
    {
        $this
            ->andWhere('cat.id IN ' . $this->getIdsAsSet($this->definition->getCategoryIds()));
        if ($this->definition->getCategoryOperator() == 'and') {
            $this
                ->groupBy('t.id')
                ->having('COUNT(DISTINCT cat.id) = ' . count($this->definition->getCategoryIds()));
        }
    }

    private function handleCompetencyTypeFilter()
    {
        $this
            ->andWhere('tct.id IN ' . $this->getIdsAsSet($this->definition->getCompetencyTypeIds()));
        if ($this->definition->getCompetencyTypeOperator() == 'and') {
            $this
                ->groupBy('t.id')
                ->having('COUNT(DISTINCT tct.id) = ' . count($this->definition->getCompetencyTypeIds()));
        }
    }

    /**
     * @param array $idArray
     *
     * @return string
     */
    private function getIdsAsSet($idArray)
    {
        array_walk($idArray, function (&$value) {
            $value = (int) trim($value);
        });

        return '('.implode(',', $idArray).')';
    }
}