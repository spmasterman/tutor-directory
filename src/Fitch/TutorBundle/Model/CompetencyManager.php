<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\CompetencyRepository;
use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Entity\Tutor;

class CompetencyManager extends BaseModelManager implements CompetencyManagerInterface
{
    /**
     * @inherit
     *
     * @param $id
     * @param Tutor $tutor
     *
     * @return Competency
     */
    public function findOrCreateCompetency($id, Tutor $tutor)
    {
        if ($id) {
            $competency = $this->findById($id);
        } else {
            $competency = $this->createCompetency();
            $tutor->addCompetency($competency);
        }

        return $competency;
    }

    /**
     * Create a new Competency.
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
     * Used  to identify logs generated by this class.
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.competency';
    }
}
