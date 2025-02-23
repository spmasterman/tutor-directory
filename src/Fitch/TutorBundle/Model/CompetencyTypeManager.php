<?php

namespace Fitch\TutorBundle\Model;

use Doctrine\ORM\EntityManager;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\CompetencyType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CompetencyTypeManager.
 */
class CompetencyTypeManager extends BaseModelManager implements CompetencyTypeManagerInterface
{
    /** @var CategoryManagerInterface $categoryManager */
    private $categoryManager;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param EntityManager            $em
     * @param string                   $class
     * @param CategoryManagerInterface $categoryManager
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        EntityManager $em,
        $class,
        CategoryManagerInterface $categoryManager
    ) {
        parent::__construct($dispatcher, $em, $class);

        $this->categoryManager = $categoryManager;
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
     * @return array
     */
    public function buildGroupedChoices()
    {
        $choices = [];
        foreach ($this->categoryManager->findAll() as $category) {
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
     * @param string $competencyTypeName
     *
     * @return CompetencyType
     */
    public function findOrCreate($competencyTypeName)
    {
        $competencyType = $this->getRepo()->findOneBy(['name' => $competencyTypeName]);

        if (!$competencyType) {
            $competencyType = $this->createEntity();
            $competencyType->setName($competencyTypeName);
            $this->saveEntity($competencyType);
        }

        return $competencyType;
    }

    /**
     * Create a new CompetencyType.
     *
     * Set its default values
     *
     * @return CompetencyType
     */
    public function createEntity()
    {
        /** @var CompetencyType $competencyType */
        $competencyType = parent::createEntity();
        $this->setDefaultCategory($competencyType);

        return $competencyType;
    }

    /**
     * @param CompetencyType $competencyType
     */
    public function setDefaultCategory(CompetencyType $competencyType)
    {
        $category = $this->categoryManager->findDefaultEntity();
        if ($category) {
            $competencyType->setCategory($category);
        }
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
