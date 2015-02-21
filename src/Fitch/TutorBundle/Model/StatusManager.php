<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\StatusRepository;
use Fitch\TutorBundle\Entity\Status;

class StatusManager extends BaseModelManager
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Status
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return Status[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @return null|Status
     */
    public function findDefaultStatus()
    {
        return $this->getRepo()->findOneBy(['default' => true]);
    }

    /**
     * @param Status $status
     * @param bool   $withFlush
     */
    public function saveStatus($status, $withFlush = true)
    {
        parent::saveEntity($status, $withFlush);
    }

    /**
     * Create a new Status.
     *
     * Set its default values
     *
     * @return Status
     */
    public function createStatus()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeStatus($id)
    {
        $status = $this->findById($id);
        parent::removeEntity($status);
    }

    /**
     * @param Status $status
     */
    public function refreshStatus(Status $status)
    {
        parent::reloadEntity($status);
    }

    /**
     * @return StatusRepository
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
        return 'fitch.manager.status';
    }
}
