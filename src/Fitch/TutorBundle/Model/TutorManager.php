<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\TutorRepository;
use Fitch\TutorBundle\Entity\Tutor;

class TutorManager extends BaseModelManager
{
   /**
     * @param $id
     * @throws EntityNotFoundException
     * @return Tutor
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return Tutor[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @param Tutor $tutor
     * @param bool $withFlush
     */
    public function saveTutor($tutor, $withFlush = true)
    {
        parent::saveEntity($tutor, $withFlush);
    }

    /**
     * Create a new Tutor
     *
     * Set its default values
     *
     * @return Tutor
     */
    public function createTutor()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeTutor($id)
    {
        $tutor = $this->findById($id);
        parent::removeEntity($tutor);
    }

    /**
     * @param Tutor $tutor
     */
    public function refreshTutor(Tutor $tutor)
    {
        parent::reloadEntity($tutor);
    }

    /**
     * @return TutorRepository
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
        return 'fitch.manager.tutor';
    }
}
