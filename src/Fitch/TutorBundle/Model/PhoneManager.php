<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\PhoneRepository;
use Fitch\TutorBundle\Entity\Phone;

class PhoneManager extends BaseModelManager implements PhoneManagerInterface
{
    /**
     * @param int $id
     */
    public function removeEntity($id)
    {
        $phone = $this->findById($id);
        parent::removeEntity($phone);
    }

    /**
     * Used  to identify logs generated by this class.
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.phone';
    }
}
