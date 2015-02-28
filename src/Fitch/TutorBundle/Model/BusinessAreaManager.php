<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Entity\NamedTraitInterface;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\BusinessArea;

/**
 * Class BusinessAreaManager.
 */
class BusinessAreaManager extends BaseModelManager implements BusinessAreaManagerInterface
{
    /**
     * @return array
     */
    public function buildChoices()
    {
        return $this->findAll();
    }

    /**
     * Returns all active categories as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * @return array
     */
    public function buildGroupedChoices()
    {
        parent::buildFlatChoices(function (NamedTraitInterface $entity) {
            return $entity->getName();
        });
    }

    /**
     * @return null|BusinessArea
     */
    public function findDefaultBusinessArea()
    {
        return $this->getRepo()->findOneBy(['default' => true]);
    }

    /**
     * Used  to identify logs generated by this class.
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.business_area';
    }
}
