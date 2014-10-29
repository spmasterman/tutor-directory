<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\OperatingRegionRepository;
use Fitch\TutorBundle\Entity\OperatingRegion;

class OperatingRegionManager extends BaseModelManager
{
    /**
     * @param $id
     * @throws EntityNotFoundException
     * @return OperatingRegion
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return OperatingRegion[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @return null|OperatingRegion
     */
    public function findDefaultOperatingRegion()
    {
        return $this->getRepo()->findOneBy(['default' => true]);
    }

    /**
     * @param OperatingRegion $operatingRegion
     * @param bool $withFlush
     */
    public function saveOperatingRegion($operatingRegion, $withFlush = true)
    {
        parent::saveEntity($operatingRegion, $withFlush);
    }

    /**
     * Create a new OperatingRegion
     *
     * Set its default values
     *
     * @return OperatingRegion
     */
    public function createOperatingRegion()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeOperatingRegion($id)
    {
        $operatingRegion = $this->findById($id);
        parent::removeEntity($operatingRegion);
    }

    /**
     * @param OperatingRegion $operatingRegion
     */
    public function refreshOperatingRegion(OperatingRegion $operatingRegion)
    {
        parent::reloadEntity($operatingRegion);
    }

    /**
     * @return OperatingRegionRepository
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
        return 'fitch.manager.operating_region';
    }
}
