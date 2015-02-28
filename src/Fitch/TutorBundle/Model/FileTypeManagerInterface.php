<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\FileType;

interface FileTypeManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return FileType
     */
    public function findById($id);

    /**
     * @return FileType[]
     */
    public function findAll();

    /**
     * @return null|FileType
     */
    public function findDefaultFileType();

    /**
     * Returns all active file types as a Array - suitable for use in "select"
     * style lists, with a preferred section.
     *
     * @return array
     */
    public function buildGroupedChoices();

    /**
     * @param FileType $fileType
     * @param bool     $withFlush
     */
    public function saveEntity($fileType, $withFlush = true);

    /**
     * Create a new FileType.
     *
     * Set its default values
     *
     * @return FileType
     */
    public function createEntity();

    /**
     * @param FileType $entity
     * @param bool $withFlush
     */
    public function removeEntity($entity, $withFlush = true);

    /**
     * @param FileType $fileType
     */
    public function reloadEntity($fileType);
}
