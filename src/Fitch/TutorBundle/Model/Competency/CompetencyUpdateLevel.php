<?php

namespace Fitch\TutorBundle\Model\Competency;

use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Model\CompetencyLevelManagerInterface;
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
     * @return \Fitch\TutorBundle\Model\CompetencyLevelManagerInterface
     */
    private function getCompetencyLevelManager()
    {
        return $this->container->get('fitch.manager.competency_level');
    }
}
