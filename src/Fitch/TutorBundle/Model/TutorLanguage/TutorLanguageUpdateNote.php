<?php

namespace Fitch\TutorBundle\Model\TutorLanguage;

use Fitch\TutorBundle\Entity\TutorLanguage;
use Fitch\TutorBundle\Model\Traits\ContainerAwareTrait;

class TutorLanguageUpdateNote implements TutorLanguageUpdateInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    public function update(TutorLanguage $tutorLanguage, $value)
    {
        $tutorLanguage->setNote($value);
    }
}
