<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Entity\NamedTraitInterface;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\ProficiencyRepository;
use Fitch\TutorBundle\Entity\Proficiency;

class ProficiencyManager extends BaseModelManager implements ProficiencyManagerInterface
{
    /**
     * @return null|Proficiency
     */
    public function findDefaultProficiency()
    {
        return $this->getRepo()->findOneBy(['default' => true]);
    }

    /**
     * @param $proficiencyName
     *
     * @return Proficiency
     */
    public function findOrCreate($proficiencyName)
    {
        $proficiency = $this->getRepo()->findOneBy(['name' => $proficiencyName]);

        if (!$proficiency) {
            $proficiency = $this->createProficiency();
            $proficiency->setName($proficiencyName);
            $this->saveProficiency($proficiency);
        }

        return $proficiency;
    }

    /**
     * Returns all active competencyLevels as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * (there's no obvious grouping, so its a flat list for Proficiency)
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
     * Create a new Proficiency.
     *
     * Set its default values
     *
     * @return Proficiency
     */
    public function createProficiency()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeProficiency($id)
    {
        $proficiency = $this->findById($id);
        parent::removeEntity($proficiency);
    }

    /**
     * @param Proficiency $proficiency
     */
    public function refreshProficiency(Proficiency $proficiency)
    {
        parent::reloadEntity($proficiency);
    }

    /**
     * @return ProficiencyRepository
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
        return 'fitch.manager.proficiency';
    }
}
