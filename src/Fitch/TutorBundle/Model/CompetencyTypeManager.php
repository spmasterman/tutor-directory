<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\CompetencyTypeRepository;
use Fitch\TutorBundle\Entity\CompetencyType;

class CompetencyTypeManager extends BaseModelManager
{
   /**
     * @param $id
     * @throws EntityNotFoundException
     * @return CompetencyType
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return CompetencyType[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @param CompetencyType $competencyType
     * @param bool $withFlush
     */
    public function saveCompetencyType($competencyType, $withFlush = true)
    {
        parent::saveEntity($competencyType, $withFlush);
    }

    /**
     * Create a new CompetencyType
     *
     * Set its default values
     *
     * @return CompetencyType
     */
    public function createCompetencyType()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeCompetencyType($id)
    {
        $competencyType = $this->findById($id);
        parent::removeEntity($competencyType);
    }

    /**
     * @param CompetencyType $competencyType
     */
    public function refreshCompetencyType(CompetencyType $competencyType)
    {
        parent::reloadEntity($competencyType);
    }

    /**
     * @return CompetencyTypeRepository
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
        return 'fitch.manager.competency_type';
    }
}
