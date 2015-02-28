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
    public function saveEntity($tutorLanguage, $withFlush = true);

    /**
     * Create a new TutorLanguage.
     *
     * Set its default values
     *
     * @return TutorLanguage
     */
    public function createEntity();

    /**
     * @param TutorLanguage $tutorLanguage
     */
    public function setDefaultProficiency(TutorLanguage $tutorLanguage);

    /**
     * @param TutorLanguage $tutorLanguage
     */
    public function reloadEntity($tutorLanguage);

    /**
     * @param TutorLanguage $entity
     * @param bool $withFlush
     */
    public function removeEntity($entity, $withFlush = true);

    /**
     * @param $id
     * @param Tutor $tutor
     *
     * @return TutorLanguage
     */
    public function findOrCreateTutorLanguage($id, Tutor $tutor);
}
