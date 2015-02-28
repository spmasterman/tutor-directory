<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Address;

interface AddressManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Address
     */
    public function findById($id);

    /**
     * @return Address[]
     */
    public function findAll();

    /**
     * @param Address $address
     * @param bool    $withFlush
     */
    public function saveEntity($address, $withFlush = true);

    /**
     * Create a new Address.
     *
     * Set its default values
     *
     * @return Address
     */
    public function createAddress();

    /**
     * @param int $id
     */
    public function removeAddress($id);

    /**
     * @param Address $address
     */
    public function refreshAddress(Address $address);
}
