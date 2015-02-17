<?php

namespace Fitch\EntityAttributeValueBundle\Listener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\UnitOfWork;
use Fitch\EntityAttributeValueBundle\Entity\Attribute;
use Doctrine\DBAL\DBALException;
use Fitch\EntityAttributeValueBundle\Entity\AttributedEntityInterface;
use Fitch\EntityAttributeValueBundle\Entity\Definition;
use ReflectionClass;

class AttributeCreatorListener
{

    /**
     * postLoad
     *
     * Creates Attributes matching the appropriate Schema for any AttributedEntityInterface entities. If a schema
     * doesn't exist, we do nothing, this allows an empty schema to be created
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();
        $entity = $eventArgs->getEntity();

        // See if the entity has the @EAV annotation:
        $reflectionClass = new \ReflectionClass($entity);
        $reader = new AnnotationReader();

        if ($reader->getClassAnnotation(
            $reflectionClass,
            'Fitch\EntityAttributeValueBundle\Annotation\Entity'
        ) != null ) {
            /** @var AttributedEntityInterface $entity */
            try {
                $schema = $em->getRepository('FitchEntityAttributeValueBundle:Schema')->findOneBy([
                    'className' => $reflectionClass->getName()
                ]);

                if ($schema !== null) {
                    foreach ($schema->getDefinitions() as $definition) {
                        $this->createAttributeForDefinition($em, $reflectionClass, $definition, $entity, $uow);
                    }
                }
            } catch (DBALException $e) {
                // Do nothing
            }
        }
    }

    /**
     * @param EntityManager $em
     * @param ReflectionClass $reflectionClass
     * @param Definition $definition
     * @param AttributedEntityInterface $entity
     * @param UnitOfWork $uow
     */
    private function createAttributeForDefinition($em, $reflectionClass, $definition, $entity, $uow)
    {
        $qb = $em->getRepository($reflectionClass->getName())->createQueryBuilder('host');

        $qb->join('host.attributes', 'a', 'WITH', 'a.definition = :definition');
        $qb->where('host = :host');
        $qb->setParameter('definition', $definition);
        $qb->setParameter('host', $entity);

        $attribute = $qb->getQuery()->getOneOrNullResult();

        if ($attribute === null) {
            $attribute = new Attribute();
            $attribute->setDefinition($definition);
            $attribute->setValue($definition->getDefault());

            $entity->addAttribute($attribute);

            if ($uow->getEntityState($entity) == UnitOfWork::STATE_MANAGED) {
                $em->persist($entity);
                $em->flush($entity);
            }
        }
    }
}
