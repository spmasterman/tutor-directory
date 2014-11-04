<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\TutorRepository;
use Fitch\TutorBundle\Entity\Tutor;

class TutorManager extends BaseModelManager
{
    /**
     * @param $id
     * @throws EntityNotFoundException
     * @return Tutor
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return Tutor[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @param Tutor $tutor
     * @param AddressManager $addressManager
     * @param CountryManager $countryManager
     */
    public function createDefaultAddressIfRequired(
        Tutor $tutor,
        AddressManager $addressManager,
        CountryManager $countryManager
    ) {
        if (!$tutor->hasAddress()) {
            $address = $addressManager->createAddress();
            $address->setCountry($countryManager->getDefaultCountry());
            $tutor->addAddress($address);
        }

    }

    public function setDefaultRegionIfRequired(Tutor $tutor, OperatingRegionManager $operatingRegionManager)
    {
        if (!$tutor->getRegion()) {
            $region = $operatingRegionManager->getDefaultRegion();
            $tutor->setRegion($region);
        }
    }








    /**
     * @param Tutor $tutor
     * @param bool $withFlush
     */
    public function saveTutor($tutor, $withFlush = true)
    {
        parent::saveEntity($tutor, $withFlush);
    }

    /**
     * Create a new Tutor
     *
     * Set its default values
     *
     * @param AddressManager $addressManager
     * @param CountryManager $countryManager
     * @param StatusManager $statusManager
     * @param OperatingRegionManager $operatingRegionManager
     *
     * @return Tutor
     */
    public function createTutor(
        AddressManager $addressManager,
        CountryManager $countryManager,
        StatusManager $statusManager,
        OperatingRegionManager $operatingRegionManager
    ) {
        /** @var Tutor $tutor */
        $tutor = parent::createEntity();
        $this->createDefaultAddressIfRequired($tutor, $addressManager, $countryManager);
        $this->setDefaultRegion($tutor, $operatingRegionManager);
        $this->setDefaultStatus($tutor, $statusManager);

        return $tutor;
    }

    /**
     * @param Tutor $tutor
     * @param OperatingRegionManager $operatingRegionManager
     */
    public function setDefaultRegion(Tutor $tutor, OperatingRegionManager $operatingRegionManager)
    {
        $tutor->setRegion($operatingRegionManager->findDefaultOperatingRegion());
    }

    /**
     * @param Tutor $tutor
     * @param StatusManager $statusManager
     */
    public function setDefaultStatus(Tutor $tutor, StatusManager $statusManager)
    {
        $tutor->setStatus($statusManager->findDefaultStatus());
    }

    /**
     * @param int $id
     */
    public function removeTutor($id)
    {
        $tutor = $this->findById($id);
        parent::removeEntity($tutor);
    }

    /**
     * @param Tutor $tutor
     */
    public function refreshTutor(Tutor $tutor)
    {
        parent::reloadEntity($tutor);
    }

    /**
     * @return TutorRepository
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
        return 'fitch.manager.tutor';
    }
}
