<?php

namespace Fitch\EntityAttributeValueBundle\Listener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\Common\Annotations\AnnotationReader;
use Fitch\EntityAttributeValueBundle\Entity\Schema;
use Doctrine\DBAL\DBALException;

class SchemaCreatorListener
{
    /**
     * Creates a schema if one doesnt exist.
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $metadata = $eventArgs->getClassMetadata();
        $reflectionClass = $metadata->getReflectionClass();

        if ($reflectionClass === null) {
            $reflectionClass = new \ReflectionClass($metadata->getName());
        }

        // See if the class has the @EAV annotation:
        $reader = new AnnotationReader();

        if ($reader->getClassAnnotation(
            $reflectionClass,
            'Fitch\EntityAttributeValueBundle\Annotation\Entity'
        ) != null) {
            // create a schema is one doesnt yet exist
            try {
                $schema = $em->getRepository('FitchEntityAttributeValueBundle:Schema')->findOneBy([
                    'className' => $metadata->getName(),
                ]);

                if ($schema === null) {
                    $schema = new Schema();
                    $schema->setClassName($metadata->getName());

                    $em->persist($schema);
                    $em->flush($schema);
                }
            } catch (DBALException $e) {
                // do nothing
            }
        }
    }
}
