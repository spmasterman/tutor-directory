<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\PhoneRepository;
use Fitch\TutorBundle\Entity\Phone;

class PhoneManager extends BaseModelManager
{
   /**
     * @param $id
     * @throws EntityNotFoundException
     * @return Phone
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return Phone[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @param Phone $phone
     * @param bool $withFlush
     */
    public function savePhone($phone, $withFlush = true)
    {
        parent::saveEntity($phone, $withFlush);
    }

    /**
     * Create a new Phone
     *
     * Set its default values
     *
     * @return Phone
     */
    public function createPhone()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removePhone($id)
    {
        $phone = $this->findById($id);
        parent::removeEntity($phone);
    }

    /**
     * @param Phone $phone
     */
    public function refreshPhone(Phone $phone)
    {
        parent::reloadEntity($phone);
    }

    /**
     * @return PhoneRepository
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
        return 'fitch.manager.phone';
    }
}
