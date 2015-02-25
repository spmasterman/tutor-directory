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
    public function saveCategory($category, $withFlush = true);

    /**
     * Create a new Category.
     *
     * Set its default values
     *
     * @return Category
     */
    public function createCategory();

    /**
     * @param int $id
     */
    public function removeCategory($id);

    /**
     * @param Category $category
     */
    public function refreshCategory(Category $category);
}
