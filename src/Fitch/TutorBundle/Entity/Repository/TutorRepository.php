<?php

namespace Fitch\TutorBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\ReportDefinition;

/**
 * TutorRepository.
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
              concat(region.name, ' (', region.code, ')') AS region,
              status.name AS status,
              GROUP_CONCAT(DISTINCT
                  CONCAT(competency_type.name, '|' ,competency_level.name, '|',IFNULL(competency.note,''))
              SEPARATOR ' ~ ') AS competency_details,
              CONCAT(
                IFNULL(tutor.bio,''),
                IFNULL(GROUP_CONCAT(DISTINCT note.body),''),
                IFNULL(GROUP_CONCAT(DISTINCT filecontent.text_content), '')
              ) AS search_dump,
              tutor.id AS id
            FROM tutor
            JOIN status ON status.id = tutor.status_id
            JOIN region ON region.id = tutor.region_id
            JOIN tutor_type ON tutor_type.id = tutor.tutor_type_id
            LEFT JOIN competency ON competency.tutor_id = tutor.id
            LEFT JOIN competency_level ON competency_level.id = competency.competency_level_id
            LEFT JOIN competency_type ON competency_type.id = competency.competency_type_id
            LEFT JOIN note ON note.tutor_id = tutor.id
            LEFT JOIN (
                 SELECT
                    tutor_id,
                    GROUP_CONCAT(DISTINCT file.text_content) AS text_content
                 FROM file
                 JOIN file_type ON file_type.id = file.file_type_id AND file_type.is_bio
            ) AS filecontent ON filecontent.tutor_id = tutor.id
            GROUP BY tutor.id
SQL;

        $this->getEntityManager()->getConnection()->prepare('SET SESSION group_concat_max_len = 1000000')->execute();
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param ReportDefinition $definition
     *
     * @return Tutor[]
     */
    public function getReportData(ReportDefinition $definition)
    {
        $qb = $this
            ->createQueryBuilder('t')
            ->where('1 = 1')
        ;

        if ($definition->isFilteredByRegion()) {
            $qb
                ->leftJoin('t.region', 'r')
                ->andWhere('r.id IN '.$definition->getRegionIDsAsSet())
            ;
        }

        if ($definition->isFilteredByStatus()) {
            $qb
                ->leftJoin('t.status', 's')
                ->andWhere('s.id IN '.$definition->getStatusIDsAsSet())
            ;
        }

        if ($definition->isFilteredByTutorType()) {
            $qb
                ->leftJoin('t.tutorType', 'tt')
                ->andWhere('tt.id IN '.$definition->getTutorTypeIDsAsSet())
            ;
        }

        if ($definition->isFilteredByLanguage()) {
            $qb
                ->leftJoin('t.tutorLanguages', 'tl')
                ->leftJoin('tl.language', 'l')
                ->andWhere('l.id IN '.$definition->getLanguageIDsAsSet())
            ;
        }

        if ($definition->isFilteredByRate()) {
            $qb
                ->join('t.rates', 'ra')
                ->join('t.currency', 'c')
                ->andWhere('ra.amount '.$definition->getRateLimitAsExpression('c'))
            ;
            if ($definition->isFilteredByRateType()) {
                $qb->andWhere('LOWER(ra.name) IN '.$definition->getRateTypesAsSet());
            }
        }

        if ($definition->isFilteredByCompetency()) {
            $qb
                ->join('t.competencies', 'tc')
                ->join('tc.competencyType', 'tct')
                ->join('tc.competencyLevel', 'tcl')
            ;

            if ($definition->isFilteredByCompetencyType()) {
                $qb->andWhere('tct.id IN '.$definition->getCompetencyTypeIdsAsSet());
            }

            if ($definition->isFilteredByCompetencyLevel()) {
                $qb->andWhere('tcl.id IN '.$definition->getCompetencyLevelIdsAsSet());
            }
        }

        return $qb->getQuery()->getResult();
    }
}
