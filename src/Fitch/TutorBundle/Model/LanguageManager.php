<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\LanguageRepository;
use Fitch\TutorBundle\Entity\Language;

class LanguageManager extends BaseModelManager
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Language
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return Language[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @param $languageName
     *
     * @return Language
     */
    public function findOrCreate($languageName)
    {
        $language = $this->getRepo()->findOneBy(['name' => $languageName]);

        if (!$language) {
            $language = $this->createLanguage();
            $language
                ->setName($languageName)
                ->setActive(true)
                ->setThreeLetterCode('')
            ;
            $this->saveLanguage($language);
        }

        return $language;
    }

    /**
     * @return Language[]
     */
    public function findAllSorted()
    {
        return $this->getRepo()->findBy([], [
            'preferred' => 'DESC',
            'active' => 'DESC',
            'name' => 'ASC',
        ]);
    }

    /**
     * @return Language[]
     */
    public function buildChoices()
    {
        return $this->getRepo()->findBy(['active' => true]);
    }

    /**
     * @return Language[]
     */
    public function buildPreferredChoices()
    {
        return $this->getRepo()->findBy(['active' => true, 'preferred' => true]);
    }

    /**
     * Returns all active languages as a Array - suitable for use in "select"
     * style lists, with a preferred section.
     *
     * @return array
     */
    public function buildGroupedChoices()
    {
        return parent::buildActiveAndPreferredChoices(function (Language $language) {
            return [
                'value' => $language->getId(),
                'text' => $language->getName(),
            ];
        });
    }

    /**
     * @param Language $language
     * @param bool     $withFlush
     */
    public function saveLanguage($language, $withFlush = true)
    {
        parent::saveEntity($language, $withFlush);
    }

    /**
     * Create a new Language.
     *
     * Set its default values
     *
     * @return Language
     */
    public function createLanguage()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeLanguage($id)
    {
        $language = $this->findById($id);
        parent::removeEntity($language);
    }

    /**
     * @param Language $language
     */
    public function refreshLanguage(Language $language)
    {
        parent::reloadEntity($language);
    }

    /**
     * @return LanguageRepository
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
        return 'fitch.manager.language';
    }
}
