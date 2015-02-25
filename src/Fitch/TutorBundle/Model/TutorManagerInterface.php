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
     * @param bool  $withFlush
     */
    public function saveTutor($tutor, $withFlush = true);

    /**
     * Create a new Tutor.
     *
     * Set its default values
     *
     * @param AddressManagerInterface         $addressManager
     * @param CountryManagerInterface         $countryManager
     * @param StatusManagerInterface          $statusManager
     * @param OperatingRegionManagerInterface $operatingRegionManager
     * @param TutorTypeManagerInterface       $tutorTypeManager
     *
     * @return Tutor
     */
    public function createTutor(
        AddressManagerInterface $addressManager,
        CountryManagerInterface $countryManager,
        StatusManagerInterface $statusManager,
        OperatingRegionManagerInterface $operatingRegionManager,
        TutorTypeManagerInterface $tutorTypeManager
    );

    /**
     * @param int $id
     */
    public function removeTutor($id);

    /**
     * @param Tutor $tutor
     */
    public function refreshTutor(Tutor $tutor);
}
