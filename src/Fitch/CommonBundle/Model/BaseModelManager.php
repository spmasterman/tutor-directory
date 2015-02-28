<?php

namespace Fitch\CommonBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Fitch\CommonBundle\Entity\ActiveAndPreferredTraitInterface;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcherInterface;

/**
 * Class BaseEntityManager.
 *
 * Creating Manager classes allows us to remove all Doctrine Entity Manager related stuff from the controller, and
 * also provides access to the custom repository class so that we can put all Doctrine related stuff in. This class
 * interface/api is therefore storage agnostic, and allows our controllers to be also.
 */
abstract class BaseModelManager
{
    use LoggerAwareTrait;

    /**
     * Holds the Symfony2 event dispatcher service.
     *
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Holds the Doctrine entity manager for database interaction.
     *
     * @var EntityManager
     */
    protected $em;

    /**
     * Entity-specific repo, useful for finding entities, for example.
     *
     * @var EntityRepository
     */
    protected $repo;

    /**
     * The Fully-Qualified Class Name for our entity.
     *
     * @var string
     */
    protected $class;

    public function __construct(EventDispatcherInterface $dispatcher, EntityManager $em, $class)
    {
        $this->dispatcher = $dispatcher;
        $this->em = $em;
        $this->class = $class;
        $this->repo = $em->getRepository($class);
    }

    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return object
     */
    public function findById($id)
    {
        $entity = $this->repo->findOneBy(['id' => $id]);

        if (!$entity) {
            throw $this->createNotFoundException("Unable to find entity with Id:{$id}.");
        }

        return $entity;
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $entities = $this->repo->findAll();

        return $entities;
    }

    /**
     * @return array
     */
    public function findAllSorted()
    {
        return $this->findAll();
    }

    /**
     * Returns all items as a Array - suitable for use in "select"
     * style lists, with a grouped sections.
     *
     * @param callable $transformFunction
     * @return array
     */
    protected function buildFlatChoices($transformFunction)
    {
        $choices = [];
        foreach ($this->findAll() as $entity) {
            if ($entity instanceof IdentityTraitInterface) {
                $choices[$entity->getId()] = $transformFunction($entity);
            }
        }

        return $choices;
    }

    /**
     * Returns all active entities as a Array - suitable for use in "select"
     * style lists, with a preferred section.
     *
     * @param callable $transformFunction
     * @return array
     */
    protected function buildActiveAndPreferredChoices($transformFunction)
    {
        $choices = [
            [
                'text' => 'Preferred',
                'children' => [],
            ],
            [
                'text' => 'Other',
                'children' => [],
            ],
        ];

        foreach ($this->findAllSorted() as $entity) {
            if ($entity instanceof ActiveAndPreferredTraitInterface) {
                if ($entity->isActive()) {
                    $key = $entity->isPreferred() ? 0 : 1;
                    $choices[$key]['children'][] = $transformFunction($entity);
                }
            }
        }

        return $choices;
    }

    protected function createNotFoundException($message = 'Entity Not Found')
    {
        return new EntityNotFoundException($message);
    }

    /**
     * @param object $entity
     * @param bool   $withFlush
     */
    public function saveEntity($entity, $withFlush = true)
    {
        if (! $this->em->contains($entity)) {
            $this->em->persist($entity);
        }

        if ($withFlush) {
            $this->em->flush();
        }
    }

    /**
     * Create a new Entity.
     *
     * Set its default values
     *
     * @return object
     */
    public function createEntity()
    {
        $class = $this->class;
        $entity = new $class();

        // Set defaults here
        return $entity;
    }

    /**
     * @param $entity
     */
    public function reloadEntity($entity)
    {
        $this->em->refresh($entity);
    }

    /**
     * @param object $entity
     * @param bool   $withFlush
     */
    public function removeEntity($entity, $withFlush = true)
    {
        if ($this->em->contains($entity)) {
            $this->em->remove($entity);
        }

        if ($withFlush) {
            $this->em->flush();
        }
    }

    /**
     * @param $level
     * @param $message
     * @param array $context
     */
    protected function log($level, $message, array $context = array())
    {
        if ($this->logger) {
            $this->logger->log($level, '['.$this->getDebugKey().'] '.$message, $context);
        }
    }

    /**
     * @return EntityRepository
     */
    protected function getRepo() {
        return $this->repo;
    }

    /**
     * Used  to identify logs generated by this class.
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'tbot.manager.base';
    }
}
