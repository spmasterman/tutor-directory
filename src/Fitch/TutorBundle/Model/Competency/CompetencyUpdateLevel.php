<?php

namespace Fitch\TutorBundle\Model\Competency;

use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Model\Interfaces\CompetencyLevelManagerInterface;
use Fitch\TutorBundle\Model\Traits\ContainerAwareTrait;

class CompetencyUpdateLevel implements CompetencyUpdateInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    public function update(Competency $competency, $value)
    {
        if ((string) (int) $value == $value) {
            // if its an integer
            $competency->setCompetencyLevel($this->getCompetencyLevelManager()->findById((int) $value));
        } else {
            $competency->setCompetencyLevel($this->getCompetencyLevelManager()->findOrCreate($value));
        }
    }

    /**
     * @return CompetencyLevelManagerInterface
     */
    private function getCompetencyLevelManager()
    {
        return $this->container->get('fitch.manager.competency_level');
    }
}
