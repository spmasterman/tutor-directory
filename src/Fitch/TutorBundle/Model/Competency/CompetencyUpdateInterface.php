<?php

namespace Fitch\TutorBundle\Model\Competency;

use Fitch\TutorBundle\Entity\Competency;

interface CompetencyUpdateInterface
{
    /**
     * @param Competency $competency
     * @param $value
     *
     * @return mixed
     */
    public function update(Competency $competency, $value);
}
