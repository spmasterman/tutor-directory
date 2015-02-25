<?php

namespace Fitch\TutorBundle\Model\Interfaces;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Language;

interface LanguageManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Language
     */
    public function findById($id);

    /**
     * @return Language[]
     */
    public function findAll();

    /**
     * @param $languageName
     *
     * @return Language
     */
    public function findOrCreate($languageName);

    /**
     * @return Language[]
     */
    public function findAllSorted();

    /**
     * @return Language[]
     */
    public function buildChoices();

    /**
     * @return Language[]
     */
    public function buildPreferredChoices();

    /**
     * Returns all active languages as a Array - suitable for use in "select"
     * style lists, with a preferred section.
     *
     * @return array
     */
    public function buildGroupedChoices();

    /**
     * @param Language $language
     * @param bool $withFlush
     */
    public function saveLanguage($language, $withFlush = true);

    /**
     * Create a new Language.
     *
     * Set its default values
     *
     * @return Language
     */
    public function createLanguage();

    /**
     * @param int $id
     */
    public function removeLanguage($id);

    /**
     * @param Language $language
     */
    public function refreshLanguage(Language $language);
}
