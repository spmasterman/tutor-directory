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
    public function buildChoices();

    /**
     * @return Country[]
     */
    public function buildPreferredChoices();

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
    public function findDefaultEntity();

    /**
     * @param Country $country
     * @param bool    $withFlush
     */
    public function saveEntity($country, $withFlush = true);

    /**
     * Create a new Country.
     *
     * Set its default values
     *
     * @return Country
     */
    public function createEntity();

    /**
     * @param Country $entity
     * @param bool $withFlush
     */
    public function removeEntity($entity, $withFlush = true);

    /**
     * @param Country $country
     */
    public function reloadEntity($country);
}
