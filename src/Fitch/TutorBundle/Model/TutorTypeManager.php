<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Entity\NamedEntityInterface;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\TutorType;
use Fitch\TutorBundle\Model\Traits\DefaultEntityTrait;

/**
 * Class TutorTypeManager
 */
class TutorTypeManager extends BaseModelManager implements TutorTypeManagerInterface
{
    use DefaultEntityTrait;

    /**
     * Returns all active tutorTypes as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * (there's no obvious grouping, so its a flat list for TutorType)
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
        return 'fitch.manager.tutor_type';
    }
}
