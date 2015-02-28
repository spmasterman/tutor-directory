<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Category;

interface CategoryManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Category
     */
    public function findById($id);

    /**
     * @return Category[]
     */
    public function findAll();

    /**
     * @return array
     */
    public function buildChoices();

    /**
     * Returns all active categories as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * @return array
     */
    public function buildGroupedChoices();

    /**
     * @return null|Category
     */
    public function findDefaultCategory();

    /**
     * @param Category $category
     * @param bool     $withFlush
     */
    public function saveEntity($category, $withFlush = true);

    /**
     * Create a new Category.
     *
     * Set its default values
     *
     * @return Category
     */
    public function createEntity();

    /**
     * @param Category $entity
     * @param bool $withFlush
     */
    public function removeEntity($entity, $withFlush = true);

    /**
     * @param Category $category
     */
    public function reloadEntity($category);
}
