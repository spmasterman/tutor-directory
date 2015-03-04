<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Entity\NamedEntityInterface;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Status;
use Fitch\TutorBundle\Model\Traits\DefaultEntityTrait;

/**
 * Class StatusManager.
 */
class StatusManager extends BaseModelManager implements StatusManagerInterface
{
    use DefaultEntityTrait;

    /**
     * Returns all active competencyLevels as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * (there's no obvious grouping, so its a flat list for Status)
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
        return 'fitch.manager.status';
    }
}
