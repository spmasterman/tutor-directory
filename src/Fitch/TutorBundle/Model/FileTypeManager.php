<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\FileTypeRepository;
use Fitch\TutorBundle\Entity\FileType;

class FileTypeManager extends BaseModelManager implements FileTypeManagerInterface
{
    /**
     * @return null|FileType
     */
    public function findDefaultFileType()
    {
        return $this->getRepo()->findOneBy(['default' => true]);
    }

    /**
     * Returns all active file types as a Array - suitable for use in "select"
     * style lists, with a preferred section.
     *
     * @return array
     */
    public function buildGroupedChoices()
    {
        return parent::buildFlatChoices(function (FileType $entity) {
            return $entity->__toString();
        });
    }

    /**
     * Create a new FileType.
     *
     * Set its default values
     *
     * @return FileType
     */
    public function createFileType()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeFileType($id)
    {
        $fileType = $this->findById($id);
        parent::removeEntity($fileType);
    }

    /**
     * @param FileType $fileType
     */
    public function refreshFileType(FileType $fileType)
    {
        parent::reloadEntity($fileType);
    }

    /**
     * @return FileTypeRepository
     */
    private function getRepo()
    {
        return $this->repo;
    }

    /**
     * Used  to identify logs generated by this class.
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.file_type';
    }
}
