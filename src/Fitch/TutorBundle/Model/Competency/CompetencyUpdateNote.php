<?php

namespace Fitch\TutorBundle\Model\Competency;

use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Model\Traits\ContainerAwareTrait;

class CompetencyUpdateNote implements CompetencyUpdateInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    public function update(Competency $competency, $value)
    {
        $competency->setNote($value);
    }
}
