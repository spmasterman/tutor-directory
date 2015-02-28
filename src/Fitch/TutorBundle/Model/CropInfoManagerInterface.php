<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\CropInfo;

interface CropInfoManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return CropInfo
     */
    public function findById($id);

    /**
     * @return CropInfo[]
     */
    public function findAll();

    /**
     * @param CropInfo $cropInfo
     * @param bool $withFlush
     */
    public function saveEntity($cropInfo, $withFlush = true);

    /**
     * Create a new CropInfo.
     *
     * Set its default values
     *
     * @return CropInfo
     */
    public function createEntity();

    /**
     * @param CropInfo $entity
     * @param bool $withFlush
     */
    public function removeEntity($entity, $withFlush = true);

    /**
     * @param CropInfo $cropInfo
     */
    public function reloadEntity($cropInfo);
}