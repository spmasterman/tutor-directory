<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\CountryRepository;
use Fitch\TutorBundle\Entity\Country;
use Fitch\TutorBundle\Model\CountryManagerInterface;

class CountryManager extends BaseModelManager implements CountryManagerInterface
{
    /**
     * @return Country[]
     */
    public function buildChoicesForAddress()
    {
        return $this->getRepo()->findBy(['active' => true]);
    }

    /**
     * @return Country[]
     */
    public function buildPreferredChoicesForAddress()
    {
        return $this->getRepo()->findBy(['active' => true, 'preferred' => true]);
    }

    /**
     * Returns all active languages as a Array - suitable for use in "select"
     * style lists, with a preferred section.
     *
     * @return array
     */
    public function buildGroupedChoices()
    {
        return parent::buildActiveAndPreferredChoices(function (Country $country) {
            return [
                'value' => $country->getId(),
                'text' => $country->getName(),
                'dialingCode' => $country->getDialingCode(),
            ];
        });
    }

    /**
     * @return Country[]
     */
    public function findAllSorted()
    {
        return $this->getRepo()->findBy(
            [],
            [
                'preferred' => 'DESC',
                'active' => 'DESC',
                'name' => 'ASC',
            ]
        );
    }

    /**
     * @return Country
     */
    public function getDefaultCountry()
    {
        return $this->getRepo()->findOneBy(['twoDigitCode' => 'GB']);
    }

    /**
     * Create a new Country.
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
     * Used  to identify logs generated by this class.
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.country';
    }
}
