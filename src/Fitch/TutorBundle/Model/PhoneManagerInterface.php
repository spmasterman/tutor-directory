<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Phone;

interface PhoneManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Phone
     */
    public function findById($id);

    /**
     * @return Phone[]
     */
    public function findAll();

    /**
     * @param Phone $phone
     * @param bool  $withFlush
     */
    public function saveEntity($phone, $withFlush = true);

    /**
     * Create a new Phone.
     *
     * Set its default values
     *
     * @return Phone
     */
    public function createEntity();

    /**
     * @param int $id
     */
    public function removeEntity($id);

    /**
     * @param Phone $phone
     */
    public function reloadEntity(Phone $phone);
}
