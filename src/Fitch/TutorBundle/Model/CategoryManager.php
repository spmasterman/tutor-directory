<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Category;

/**
 * Class CategoryManager.
 */
class CategoryManager extends BaseModelManager implements CategoryManagerInterface
{
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
        $choices = [];
        foreach ($this->findAll() as $category) {
            /* @var Category $category */
            $choices[$category->getId()] = $category->getName();
        }

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
     * Used  to identify logs generated by this class.
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.category';
    }
}
