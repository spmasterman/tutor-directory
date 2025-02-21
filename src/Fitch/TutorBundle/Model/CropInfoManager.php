<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Model\BaseModelManager;

/**
 * Class CropInfoManager.
 */
class CropInfoManager extends BaseModelManager implements CropInfoManagerInterface
{
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
