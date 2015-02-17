<?php

namespace Fitch\UserBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Exception\InappropriateActionException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\EntityAttributeValueBundle\Entity\Attribute;
use Fitch\EntityAttributeValueBundle\Entity\Definition;
use Fitch\EntityAttributeValueBundle\Entity\Option;
use Fitch\EntityAttributeValueBundle\Entity\Schema;
use Fitch\UserBundle\Entity\Repository\UserRepository;
use Fitch\UserBundle\Entity\User;

class UserManager extends BaseModelManager
{
    const EAV_GROUP_WIDGET_STATES = "widget.state";

    /**
     * @param $id
     * @throws EntityNotFoundException
     * @return User
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return User[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @param User $user
     * @param $name
     * @throws EntityNotFoundException
     * @return Attribute
     */
    public function findAttributeByName(User $user, $name)
    {
        foreach ($user->getAttributes() as $attribute) {
            if ($attribute->getDefinition()->getName() == $name) {
                return $attribute;
            }
        }
        throw new EntityNotFoundException("Attribute {$name} not found on user ". $user->getUsername());
    }

    /**
     * @param $user
     * @param $name
     *
     * @return array
     */
    public function findAttributeGroupAsMap(User $user, $name)
    {
        $map = [];
        foreach ($user->getAttributes() as $attribute) {
            /** @var Attribute $attribute */
            $definition = $attribute->getDefinition();
            if ($definition->getGroup() == $name) {
                $map[$attribute->getDefinition()->getName()] = $attribute->getValue();
            }
        }
        return $map;
    }

    public function createWidgetControlDefinition($widgetName)
    {
        $humanName = ucfirst(str_replace('-', ' ', $widgetName));
        $definition = new Definition();
        $definition
            ->setName($widgetName)
            ->setLabel($humanName)
            ->setDescription(sprintf('Is the %s widget open or closed', $humanName))
            ->setType('choice')
            ->setUnit('')
            ->setSortOrder(1)
            ->setGroup(self::EAV_GROUP_WIDGET_STATES)
        ;

        $openOption = new Option();
        $openOption->setName('open');
        $closedOption = new Option();
        $closedOption->setName('closed');

        $definition->addOption($openOption);
        $definition->addOption($closedOption);
        $definition->setDefault($openOption->getName());

        $schemaRepo = $this->em->getRepository('Fitch\EntityAttributeValueBundle\Entity\Schema');
        $schema = $schemaRepo->findOneBy(['className' => 'Fitch\UserBundle\Entity\User']);
        /** @var Schema $schema */
        $schema->addDefinition($definition);
        $this->em->persist($definition);
        $this->em->flush();
    }


    /**
     * @param User $user
     * @return array
     */
    public function getLogs(User $user)
    {
        return $this->em->getRepository('Gedmo\Loggable\Entity\LogEntry')->getLogEntries($user);
    }


    /**
     * @param User $user
     * @param bool $withFlush
     */
    public function saveUser(User $user, $withFlush = true)
    {
        parent::saveEntity($user, $withFlush);
    }

    /**
     * Create a new User
     *
     * Set its default values
     *
     * @return User
     */
    public function createUser()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     * @throws InappropriateActionException
     */
    public function removeUser($id)
    {
        $user = $this->findById($id);
        parent::removeEntity($user);
    }

    /**
     * @param User $user
     */
    public function refreshUser(User $user)
    {
        parent::reloadEntity($user);
    }

    /**
     * @return UserRepository
     */
    private function getRepo()
    {
        return $this->repo;
    }

    /**
     * Used  to identify logs generated by this class
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.user';
    }
}
