<?php

namespace Fitch\TutorBundle\Model\Interfaces;

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
    public function savePhone($phone, $withFlush = true);

    /**
     * Create a new Phone.
     *
     * Set its default values
     *
     * @return Phone
     */
    public function createPhone();

    /**
     * @param int $id
     */
    public function removePhone($id);

    /**
     * @param Phone $phone
     */
    public function refreshPhone(Phone $phone);
}
