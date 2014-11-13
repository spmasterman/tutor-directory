<?php

namespace Fitch\TutorBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * TutorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TutorRepository extends EntityRepository
{

    public function findAllForTable()
    {
        $sql = <<<'SQL'

            SELECT
              tutor.name AS fullname,
              tutor_type.name AS ttype,
              region.name AS region,
              status.name AS status,
              GROUP_CONCAT(
                  CONCAT(competency_type.name, '|' ,competency_level.name, '|',IFNULL(competency.note,''))
              SEPARATOR ' ~ ') AS competency_details
            FROM tutor
            JOIN status ON status.id = tutor.status_id
            JOIN region ON region.id = tutor.region_id
            JOIN tutor_type ON tutor_type.id = tutor.tutor_type_id
            LEFT JOIN competency ON competency.tutor_id = tutor.id
            LEFT JOIN competency_level ON competency_level.id = competency.competency_level_id
            LEFT JOIN competency_type ON competency_type.id = competency.competency_type_id
            GROUP BY tutor.id
SQL;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
