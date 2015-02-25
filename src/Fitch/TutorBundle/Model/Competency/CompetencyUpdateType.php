<?php

namespace Fitch\TutorBundle\Model\Competency;

use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\Interfaces\CategoryManagerInterface;
use Fitch\TutorBundle\Model\Interfaces\CompetencyLevelManagerInterface;
use Fitch\TutorBundle\Model\Interfaces\CompetencyManagerInterface;
use Fitch\TutorBundle\Model\Interfaces\CompetencyTypeManagerInterface;
use Fitch\TutorBundle\Model\Interfaces\EmailManagerInterface;
use Fitch\TutorBundle\Model\Interfaces\TutorManagerInterface;
use Fitch\TutorBundle\Model\Traits\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;

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
