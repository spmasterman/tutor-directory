<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\CompetencyRepository;
use Fitch\TutorBundle\Entity\Competency;

class CompetencyManager extends BaseModelManager
{
   /**
     * @param $id
     * @throws EntityNotFoundException
     * @return Competency
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return Competency[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @param Competency $competency
     * @param bool $withFlush
     */
    public function saveCompetency($competency, $withFlush = true)
    {
        parent::saveEntity($competency, $withFlush);
    }

    /**
     * Create a new Competency
     *
     * Set its default values
     *
     * @return Competency
     */
    public function createCompetency()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeCompetency($id)
    {
        $competency = $this->findById($id);
        parent::removeEntity($competency);
    }

    /**
     * @param Competency $competency
     */
    public function refreshCompetency(Competency $competency)
    {
        parent::reloadEntity($competency);
    }

    /**
     * @return CompetencyRepository
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
        return 'fitch.manager.competency';
    }
}
