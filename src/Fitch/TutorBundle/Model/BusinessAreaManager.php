<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Entity\NamedEntityInterface;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\BusinessArea;
use Fitch\TutorBundle\Model\Traits\DefaultEntityTrait;

/**
 * Class BusinessAreaManager.
 */
class BusinessAreaManager extends BaseModelManager implements BusinessAreaManagerInterface
{
    use DefaultEntityTrait;

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
        return parent::buildFlatChoices(function (NamedEntityInterface $entity) {
            return $entity->getName();
        });
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
