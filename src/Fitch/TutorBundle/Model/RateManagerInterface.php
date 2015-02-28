<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Rate;

interface RateManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Rate
     */
    public function findById($id);

    /**
     * @return Rate[]
     */
    public function findAll();

    /**
     * @return array
     */
    public function buildChoices();

    /**
     * @param Rate $rate
     *
     * @return array
     */
    public function getLogs(Rate $rate);

    /**
     * @param Rate $rate
     * @param bool $withFlush
     */
    public function saveEntity($rate, $withFlush = true);

    /**
     * Create a new Rate.
     *
     * Set its default values
     *
     * @return Rate
     */
    public function createEntity();

    /**
     * @param int $id
     */
    public function removeEntity($id);

    /**
     * @param Rate $rate
     */
    public function reloadEntity($rate);
}
