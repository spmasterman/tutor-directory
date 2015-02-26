<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\CompetencyTypeRepository;
use Fitch\TutorBundle\Entity\CompetencyType;
use Fitch\TutorBundle\Model\CategoryManagerInterface;
use Fitch\TutorBundle\Model\CompetencyTypeManagerInterface;

class CompetencyTypeManager extends BaseModelManager implements CompetencyTypeManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return CompetencyType
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return CompetencyType[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @return CompetencyType[]
     */
    public function findAllSorted()
    {
        return $this->getRepo()->findBy([], [
            'category' => 'ASC',
            'name' => 'ASC',
        ]);
    }

    /**
     * @return array
     */
    public function buildChoices()
    {
        return $this->findAll();
    }

    /**
     * Returns all active competencyTypes as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * @param CategoryManagerInterface $categoryManager
     *
     * @return array
     */
    public function buildGroupedChoices(CategoryManagerInterface $categoryManager)
    {
        $choices = [];
        foreach ($categoryManager->findAll() as $category) {
            $choices[$category->getId()] = ['text' => $category->__toString(), 'children' => []];
        }

        foreach ($this->findAllSorted() as $competencyType) {
            $key = $competencyType->getCategory()->getId();
            $choices[$key]['children'][] = [
                'value' => $competencyType->getId(),
                'text' => $competencyType->getName(),
            ];
        }

        // remove any empty categories
        foreach ($choices as $key => $choice) {
            if (count($choice['children']) == 0) {
                unset($choices[$key]);
            }
        }

        // don't return the k=>v array, it doesnt encode to JSON in the same way - we just want the values
        return array_values($choices);
    }

    /**
     * @param string          $competencyTypeName
     * @param CategoryManagerInterface $categoryManager
     *
     * @return CompetencyType
     */
    public function findOrCreate($competencyTypeName, CategoryManagerInterface $categoryManager)
    {
        $competencyType = $this->getRepo()->findOneBy(['name' => $competencyTypeName]);

        if (!$competencyType) {
            $competencyType = $this->createCompetencyType($categoryManager);
            $competencyType->setName($competencyTypeName);
            $this->saveCompetencyType($competencyType);
        }

        return $competencyType;
    }

    /**
     * @param CompetencyType $competencyType
     * @param bool           $withFlush
     */
    public function saveCompetencyType($competencyType, $withFlush = true)
    {
        parent::saveEntity($competencyType, $withFlush);
    }

    /**
     * Create a new CompetencyType.
     *
     * Set its default values
     *
     * @param CategoryManagerInterface $categoryManager
     *
     * @return CompetencyType
     */
    public function createCompetencyType(
        CategoryManagerInterface $categoryManager
    ) {
        /** @var CompetencyType $competencyType */
        $competencyType = parent::createEntity();
        $this->setDefaultCategory($competencyType, $categoryManager);

        return $competencyType;
    }

    /**
     * @param CompetencyType  $competencyType
     * @param CategoryManagerInterface $categoryManager
     */
    public function setDefaultCategory(CompetencyType $competencyType, CategoryManagerInterface $categoryManager)
    {
        $category = $categoryManager->findDefaultCategory();
        if ($category) {
            $competencyType->setCategory($category);
        }
    }

    /**
     * @param int $id
     */
    public function removeCompetencyType($id)
    {
        $competencyType = $this->findById($id);
        parent::removeEntity($competencyType);
    }

    /**
     * @param CompetencyType $competencyType
     */
    public function refreshCompetencyType(CompetencyType $competencyType)
    {
        parent::reloadEntity($competencyType);
    }

    /**
     * @return CompetencyTypeRepository
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
        return 'fitch.manager.competency_type';
    }
}
