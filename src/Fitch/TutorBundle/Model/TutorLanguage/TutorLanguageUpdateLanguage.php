<?php

namespace Fitch\TutorBundle\Model\TutorLanguage;

use Fitch\TutorBundle\Entity\TutorLanguage;
use Fitch\TutorBundle\Model\Interfaces\LanguageManagerInterface;
use Fitch\TutorBundle\Model\Traits\ContainerAwareTrait;

class TutorLanguageUpdateLanguage implements TutorLanguageUpdateInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    public function update(TutorLanguage $tutorLanguage, $value)
    {
        if ((string) (int) $value == $value) {
            // if its an integer
            $tutorLanguage->setLanguage($this->getLanguageManager()->findById((int) $value));
        } else {
            $tutorLanguage->setLanguage($this->getLanguageManager()->findOrCreate($value));
        }
    }

    /**
     * @return LanguageManagerInterface
     */
    private function getLanguageManager()
    {
        return $this->container->get('fitch.manager.language');
    }
}
