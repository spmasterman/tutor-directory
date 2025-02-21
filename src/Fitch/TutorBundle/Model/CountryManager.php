<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Country;

/**
 * Class CountryManager.
 */
class CountryManager extends BaseModelManager implements CountryManagerInterface
{
    /**
     * @return Country[]
     */
    public function buildChoices()
    {
        return $this->getRepo()->findBy(['active' => true]);
    }

    /**
     * @return Country[]
     */
    public function buildPreferredChoices()
    {
        return $this->getRepo()->findBy(['active' => true, 'preferred' => true]);
    }

    /**
     * Returns all active languages as a Array - suitable for use in "select"
     * style lists, with a preferred section.
     *
     * @return array
     */
    public function buildGroupedChoices()
    {
        return parent::buildActiveAndPreferredChoices(function (Country $country) {
            return [
                'value' => $country->getId(),
                'text' => $country->getName(),
                'dialingCode' => $country->getDialingCode(),
            ];
        });
    }

    /**
     * @return Country[]
     */
    public function findAllSorted()
    {
        return $this->getRepo()->findBy(
            [],
            [
                'preferred' => 'DESC',
                'active' => 'DESC',
                'name' => 'ASC',
            ]
        );
    }

    /**
     * @return Country
     */
    public function findDefaultEntity()
    {
        return $this->getRepo()->findOneBy(['twoDigitCode' => 'GB']);
    }

    /**
     * Used  to identify logs generated by this class.
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.country';
    }
}
