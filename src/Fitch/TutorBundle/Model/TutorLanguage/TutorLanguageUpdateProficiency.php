<?php

namespace Fitch\TutorBundle\Model\TutorLanguage;

use Fitch\TutorBundle\Entity\TutorLanguage;
use Fitch\TutorBundle\Model\Traits\ContainerAwareTrait;

class TutorLanguageUpdateProficiency implements TutorLanguageUpdateInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    public function update(TutorLanguage $tutorLanguage, $value)
    {
        if ((string) (int) $value == $value) {
            // if its an integer
            $tutorLanguage->setProficiency($this->getProficiencyManager()->findById((int) $value));
        } else {
            $tutorLanguage->setProficiency($this->getProficiencyManager()->findOrCreate($value));
        }
    }

    /**
     * @return \Fitch\TutorBundle\Model\ProficiencyManagerInterface
     */
    private function getProficiencyManager()
    {
        return $this->container->get('fitch.manager.proficiency');
    }
}
