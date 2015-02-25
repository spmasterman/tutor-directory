<?php

namespace Fitch\TutorBundle\Model\TutorLanguage;

use Fitch\TutorBundle\Entity\TutorLanguage;

interface TutorLanguageUpdateInterface
{
    /**
     * @param TutorLanguage $tutorlanguage
     * @param $value
     * @return mixed
     */
    public function update(TutorLanguage $tutorlanguage, $value);
}
