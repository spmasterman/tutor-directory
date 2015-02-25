<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Tutor;

interface TutorManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Tutor
     */
    public function findById($id);

    /**
     * @return Tutor[]
     */
    public function findAll();

    /**
     * @return array
     */
    public function populateTable();

    /**
     * @param ReportDefinition $definition
     *
     * @return Tutor[]
     */
    public function getReportData(ReportDefinition $definition);

    /**
     * @param Tutor $tutor
     * @param AddressManager $addressManager
     * @param CountryManager $countryManager
     */
    public function createDefaultAddressIfRequired(Tutor $tutor, AddressManager $addressManager, CountryManager $countryManager);

    /**
     * @param Tutor $tutor
     * @param bool $withFlush
     */
    public function saveTutor($tutor, $withFlush = true);

    /**
     * Create a new Tutor.
     *
     * Set its default values
     *
     * @param AddressManager $addressManager
     * @param CountryManager $countryManager
     * @param StatusManager $statusManager
     * @param OperatingRegionManager $operatingRegionManager
     * @param TutorTypeManager $tutorTypeManager
     *
     * @return Tutor
     */
    public function createTutor(AddressManager $addressManager, CountryManager $countryManager, StatusManager $statusManager, OperatingRegionManager $operatingRegionManager, TutorTypeManager $tutorTypeManager);

    /**
     * @param Tutor $tutor
     * @param OperatingRegionManager $operatingRegionManager
     */
    public function setDefaultRegion(Tutor $tutor, OperatingRegionManager $operatingRegionManager);

    /**
     * @param Tutor $tutor
     * @param StatusManager $statusManager
     */
    public function setDefaultStatus(Tutor $tutor, StatusManager $statusManager);

    /**
     * @param Tutor $tutor
     * @param TutorTypeManager $tutorTypeManager
     */
    public function setDefaultTutorType(Tutor $tutor, TutorTypeManager $tutorTypeManager);

    /**
     * @param int $id
     */
    public function removeTutor($id);

    /**
     * @param Tutor $tutor
     */
    public function refreshTutor(Tutor $tutor);
}