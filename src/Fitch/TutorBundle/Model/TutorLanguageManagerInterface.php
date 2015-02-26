<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Entity\TutorLanguage;

interface TutorLanguageManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return TutorLanguage
     */
    public function findById($id);

    /**
     * @return TutorLanguage[]
     */
    public function findAll();

    /**
     * @param TutorLanguage $tutorLanguage
     * @param bool          $withFlush
     */
    public function saveTutorLanguage($tutorLanguage, $withFlush = true);

    /**
     * Create a new TutorLanguage.
     *
     * Set its default values
     *
     * @return TutorLanguage
     */
    public function createTutorLanguage();

    /**
     * @param TutorLanguage $tutorLanguage
     */
    public function setDefaultProficiency(TutorLanguage $tutorLanguage);

    /**
     * @param int $id
     */
    public function removeTutorLanguage($id);

    /**
     * @param TutorLanguage $tutorLanguage
     */
    public function refreshTutorLanguage(TutorLanguage $tutorLanguage);

    /**
     * @param $id
     * @param Tutor $tutor
     *
     * @return TutorLanguage
     */
    public function findOrCreateTutorLanguage($id, Tutor $tutor);
}
