<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Entity\NamedTraitInterface;
use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\TutorTypeRepository;
use Fitch\TutorBundle\Entity\TutorType;
use Fitch\TutorBundle\Model\Interfaces\TutorTypeManagerInterface;

class TutorTypeManager extends BaseModelManager implements TutorTypeManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return TutorType
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return TutorType[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @return null|TutorType
     */
    public function findDefaultTutorType()
    {
        return $this->getRepo()->findOneBy(['default' => true]);
    }

    /**
     * Returns all active tutorTypes as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * (there's no obvious grouping, so its a flat list for TutorType)
     *
     * @return array
     */
    public function buildGroupedChoices()
    {
        return parent::buildFlatChoices(function (NamedTraitInterface $entity) {
            return $entity->getName();
        });
    }

    /**
     * @param TutorType $tutorType
     * @param bool      $withFlush
     */
    public function saveTutorType($tutorType, $withFlush = true)
    {
        parent::saveEntity($tutorType, $withFlush);
    }

    /**
     * Create a new TutorType.
     *
     * Set its default values
     *
     * @return TutorType
     */
    public function createTutorType()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeTutorType($id)
    {
        $tutorType = $this->findById($id);
        parent::removeEntity($tutorType);
    }

    /**
     * @param TutorType $tutorType
     */
    public function refreshTutorType(TutorType $tutorType)
    {
        parent::reloadEntity($tutorType);
    }

    /**
     * @return TutorTypeRepository
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
        return 'fitch.manager.tutor_type';
    }
}
