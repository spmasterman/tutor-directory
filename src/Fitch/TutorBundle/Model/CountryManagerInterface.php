<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Country;

interface CountryManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Country
     */
    public function findById($id);

    /**
     * @return Country[]
     */
    public function findAll();

    /**
     * @return Country[]
     */
    public function buildChoicesForAddress();

    /**
     * @return Country[]
     */
    public function buildPreferredChoicesForAddress();

    /**
     * Returns all active languages as a Array - suitable for use in "select"
     * style lists, with a preferred section.
     *
     * @return array
     */
    public function buildGroupedChoices();

    /**
     * @return Country[]
     */
    public function findAllSorted();

    /**
     * @return Country
     */
    public function getDefaultCountry();

    /**
     * @param Country $country
     * @param bool    $withFlush
     */
    public function saveCountry($country, $withFlush = true);

    /**
     * Create a new Country.
     *
     * Set its default values
     *
     * @return Country
     */
    public function createCountry();

    /**
     * @param int $id
     */
    public function removeCountry($id);

    /**
     * @param Country $country
     */
    public function refreshCountry(Country $country);
}
