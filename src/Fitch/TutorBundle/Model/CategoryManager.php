<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\CategoryRepository;
use Fitch\TutorBundle\Entity\Category;

class CategoryManager extends BaseModelManager
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Category
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return Category[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @return array
     */
    public function buildChoices()
    {
        return $this->findAll();
    }

    /**
     * Returns all active categories as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * @return array
     */
    public function buildGroupedChoices()
    {
        // FOR NOW - THIS JUST RETURNS K=>V
        // BUT WHEN THE SKILL CATEGORY COMES IN THIS WILL BE USED
        $choices = [];
        foreach ($this->findAll() as $category) {
            $choices[$category->getId()] = $category->getName();
        }

// Something like this but not "preferred" - SkillCategory instead...
//        $choices = [
//            [
//                'text' => 'Preferred',
//                'children' => []
//            ],
//            [
//                'text' => 'Other',
//                'children' => []
//            ]
//        ];
//
//        foreach($this->findAllSorted() as $language) {
//            if ($language->isActive()) {
//                $key = $language->isPreferred() ? 0 : 1;
//                $choices[$key]['children'][] = [
//                    'value' => $language->getId(),
//                    'text' => $language->getName(),
//                ];
//            }
//        }
        return $choices;
    }

    /**
     * @return null|Category
     */
    public function findDefaultCategory()
    {
        return $this->getRepo()->findOneBy(['default' => true]);
    }

    /**
     * @param Category $category
     * @param bool $withFlush
     */
    public function saveCategory($category, $withFlush = true)
    {
        parent::saveEntity($category, $withFlush);
    }

    /**
     * Create a new Category.
     *
     * Set its default values
     *
     * @return Category
     */
    public function createCategory()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeCategory($id)
    {
        $category = $this->findById($id);
        parent::removeEntity($category);
    }

    /**
     * @param Category $category
     */
    public function refreshCategory(Category $category)
    {
        parent::reloadEntity($category);
    }

    /**
     * @return CategoryRepository
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
        return 'fitch.manager.category';
    }
}
