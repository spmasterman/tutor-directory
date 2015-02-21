<?php

namespace Fitch\TutorBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * RateRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RateRepository extends EntityRepository
{
    public function getActiveRateTypes()
    {
        $rateTypesQuery = $this->createQueryBuilder('r')
            ->select('r.name')
            ->distinct()
            ->orderBy('r.name')
            ->getQuery();

        return $rateTypesQuery->getResult();
    }
}
