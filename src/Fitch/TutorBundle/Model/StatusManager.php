<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Entity\NamedTraitInterface;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Status;

/**
 * Class StatusManager.
 */
class StatusManager extends BaseModelManager implements StatusManagerInterface
{
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
        return parent::buildFlatChoices(function (NamedTraitInterface $entity) {
            return $entity->getName();
        });
    }

    /**
     * @return null|Status
     */
    public function findDefaultStatus()
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
        return 'fitch.manager.status';
    }
}
