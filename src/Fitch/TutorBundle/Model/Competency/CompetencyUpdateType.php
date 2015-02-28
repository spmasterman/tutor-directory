<?php

namespace Fitch\TutorBundle\Model\Competency;

use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Model\CategoryManagerInterface;
use Fitch\TutorBundle\Model\CompetencyTypeManagerInterface;
use Fitch\TutorBundle\Model\Traits\ContainerAwareTrait;

class CompetencyUpdateType implements CompetencyUpdateInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    public function update(Competency $competency, $value)
    {
        if ((string) (int) $value == $value) {
            // if its an integer
            $competency->setCompetencyType($this->getCompetencyTypeManager()->findById((int) $value));
        } else {
            $competency->setCompetencyType(
                $this->getCompetencyTypeManager()->findOrCreate($value, $this->getCategoryManager())
            );
        }
    }

    /**
     * @return CompetencyTypeManagerInterface
     */
    private function getCompetencyTypeManager()
    {
        return $this->container->get('fitch.manager.competency_type');
    }

    /**
     * @return CategoryManagerInterface
     */
    private function getCategoryManager()
    {
        return $this->container->get('fitch.manager.category');
    }
}
