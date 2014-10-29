<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\CountryRepository;
use Fitch\TutorBundle\Entity\Country;

class CountryManager extends BaseModelManager
{
    /**
     * @param $id
     * @throws EntityNotFoundException
     * @return Country
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return Country[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @return Country[]
     */
    public function buildChoicesForAddress()
    {
        return $this->findAll();
    }

    /**
     * @return Country[]
     */
    public function buildPreferredChoicesForAddress()
    {
        return $this->getRepo()->findBy(['highlighted' => true]);
    }

    /**
     * @return Country[]
     */
    public function findAllSorted()
    {
        return $this->getRepo()->findBy([],  array('highlighted' => 'DESC', 'name' => 'ASC'));
    }

    /**
     * @return Country
     */
    public function getDefaultCountry()
    {
        return $this->getRepo()->findOneBy(['twoDigitCode' => 'GB']);
    }

    /**
     * @param Country $country
     * @param bool $withFlush
     */
    public function saveCountry($country, $withFlush = true)
    {
        parent::saveEntity($country, $withFlush);
    }

    /**
     * Create a new Country
     *
     * Set its default values
     *
     * @return Country
     */
    public function createCountry()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeCountry($id)
    {
        $country = $this->findById($id);
        parent::removeEntity($country);
    }

    /**
     * @param Country $country
     */
    public function refreshCountry(Country $country)
    {
        parent::reloadEntity($country);
    }

    /**
     * @return CountryRepository
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
        return 'fitch.manager.country';
    }
}
