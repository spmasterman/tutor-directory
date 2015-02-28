<?php

namespace Fitch\TutorBundle\Model\TutorLanguage;

use Fitch\TutorBundle\Entity\TutorLanguage;

interface TutorLanguageUpdateInterface
{
    /**
     * @param TutorLanguage $tutorLanguage
     * @param $value
     * @return mixed
     */
    public function update(TutorLanguage $tutorLanguage, $value);
}
