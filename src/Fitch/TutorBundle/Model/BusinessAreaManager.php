<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Entity\NamedTraitInterface;
use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\BusinessAreaRepository;
use Fitch\TutorBundle\Entity\BusinessArea;

class BusinessAreaManager extends BaseModelManager
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return BusinessArea
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return BusinessArea[]
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
     * Returns all active categories as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * @return array
     */
    public function buildGroupedChoices()
    {
        parent::buildFlatChoices(function(NamedTraitInterface $entity) {
            return $entity->getName();
        });
    }

    /**
     * @return null|BusinessArea
     */
    public function findDefaultBusinessArea()
    {
        return $this->getRepo()->findOneBy(['default' => true]);
    }

    /**
     * @param BusinessArea $businessArea
     * @param bool     $withFlush
     */
    public function saveBusinessArea($businessArea, $withFlush = true)
    {
        parent::saveEntity($businessArea, $withFlush);
    }

    /**
     * Create a new BusinessArea.
     *
     * Set its default values
     *
     * @return BusinessArea
     */
    public function createBusinessArea()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeBusinessArea($id)
    {
        $businessArea = $this->findById($id);
        parent::removeEntity($businessArea);
    }

    /**
     * @param BusinessArea $businessArea
     */
    public function refreshBusinessArea(BusinessArea $businessArea)
    {
        parent::reloadEntity($businessArea);
    }

    /**
     * @return BusinessAreaRepository
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
        return 'fitch.manager.business_area';
    }
}
