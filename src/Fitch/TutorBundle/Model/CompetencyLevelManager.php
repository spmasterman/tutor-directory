<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\CompetencyLevelRepository;
use Fitch\TutorBundle\Entity\CompetencyLevel;

class CompetencyLevelManager extends BaseModelManager
{
   /**
     * @param $id
     * @throws EntityNotFoundException
     * @return CompetencyLevel
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return CompetencyLevel[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @return array
     */
    public function buildChoices()
    {
        return $this->findAll();
    }

    /**
     * @param $levelName
     *
     * @return CompetencyLevel
     */
    public function findOrCreate($levelName)
    {
        $competencyLevel = $this->getRepo()->findOneBy(['name' => $levelName]);

        if (!$competencyLevel) {
            $competencyLevel = $this->createCompetencyLevel();
            $competencyLevel->setName($levelName);
            $this->saveCompetencyLevel($competencyLevel);
        }

        return $competencyLevel;
    }

    /**
     * @param CompetencyLevel $competencyLevel
     * @param bool $withFlush
     */
    public function saveCompetencyLevel($competencyLevel, $withFlush = true)
    {
        parent::saveEntity($competencyLevel, $withFlush);
    }

    /**
     * Create a new CompetencyLevel
     *
     * Set its default values
     *
     * @return CompetencyLevel
     */
    public function createCompetencyLevel()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeCompetencyLevel($id)
    {
        $competencyLevel = $this->findById($id);
        parent::removeEntity($competencyLevel);
    }

    /**
     * @param CompetencyLevel $competencyLevel
     */
    public function refreshCompetencyLevel(CompetencyLevel $competencyLevel)
    {
        parent::reloadEntity($competencyLevel);
    }

    /**
     * @return CompetencyLevelRepository
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
        return 'fitch.manager.competency_level';
    }
}
