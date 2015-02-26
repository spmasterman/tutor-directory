<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\AddressRepository;
use Fitch\TutorBundle\Entity\Address;
use Fitch\TutorBundle\Model\AddressManagerInterface;

class AddressManager extends BaseModelManager implements AddressManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Address
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return Address[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @param Address $address
     * @param bool    $withFlush
     */
    public function saveAddress($address, $withFlush = true)
    {
        parent::saveEntity($address, $withFlush);
    }

    /**
     * Create a new Address.
     *
     * Set its default values
     *
     * @return Address
     */
    public function createAddress()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeAddress($id)
    {
        $address = $this->findById($id);
        parent::removeEntity($address);
    }

    /**
     * @param Address $address
     */
    public function refreshAddress(Address $address)
    {
        parent::reloadEntity($address);
    }

    /**
     * @return AddressRepository
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
        return 'fitch.manager.address';
    }
}
