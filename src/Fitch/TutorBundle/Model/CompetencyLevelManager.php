<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Entity\NamedTraitInterface;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\CompetencyLevelRepository;
use Fitch\TutorBundle\Entity\CompetencyLevel;

class CompetencyLevelManager extends BaseModelManager implements CompetencyLevelManagerInterface
{
    /**
     * @return array
     */
    public function buildChoices()
    {
        return $this->findAll();
    }

    /**
     * Returns all active competencyLevels as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * (there's no obvious grouping, so its a flat list for CompetencyLevel)
     *
     * @return array
     */
    public function buildGroupedChoices()
    {
        return parent::buildFlatChoices(function (NamedTraitInterface $entity) {
            return $entity->getName();
        });
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
     * Create a new CompetencyLevel.
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
     * Used  to identify logs generated by this class.
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.competency_level';
    }
}
