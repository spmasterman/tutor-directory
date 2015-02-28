<?php
/**
 * Created by PhpStorm.
 * User: smasterman
 * Date: 28/02/15
 * Time: 00:05.
 */

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\File;
use Oneup\UploaderBundle\Uploader\File\GaufretteFile;
use Symfony\Component\HttpFoundation\Request;

interface FileManagerInterface
{
    /**
     * @param int $id
     *
     * @throws EntityNotFoundException
     *
     * @return File
     */
    public function findById($id);

    /**
     * @return File[]
     */
    public function findAll();

    /**
     * @param File $file
     * @param bool $withFlush
     */
    public function saveEntity($file, $withFlush = true);

    /**
     * @param Request       $request
     * @param GaufretteFile $gaufretteFile
     * @param array         $metaInfo
     *
     * @return File
     */
    public function setMetaInfo(Request $request, GaufretteFile $gaufretteFile, $metaInfo);

    /**
     * Create a new File.
     *
     * Set its default values
     *
     * @return File
     */
    public function createEntity();

    /**
     * @param File $entity
     * @param bool $withFlush
     */
    public function removeEntity($entity, $withFlush = true);

    /**
     * @param File $file
     */
    public function reloadEntity($file);
}
