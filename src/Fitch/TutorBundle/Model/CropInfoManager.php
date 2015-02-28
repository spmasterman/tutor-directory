<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\CropInfoRepository;
use Fitch\TutorBundle\Entity\CropInfo;

class CropInfoManager extends BaseModelManager implements CropInfoManagerInterface
{

    /**
     * @param int $id
     */
    public function removeEntity($id)
    {
        $cropInfo = $this->findById($id);
        parent::removeEntity($cropInfo);
    }

    /**
     * Used  to identify logs generated by this class.
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.crop_info';
    }
}
