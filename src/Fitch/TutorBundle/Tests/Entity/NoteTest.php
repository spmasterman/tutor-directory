<?php

namespace Fitch\TutorBundle\Tests\Entity;

use DateInterval;
use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\NoteManagerInterface;
use ReflectionClass;

/**
 * Class NoteTest.
 */
class NoteTest extends FixturesWebTestCase
{
    /**
     *
     */
    public function testProvenance()
    {

        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals('Test Note One', $entityOne->getBody());
        $this->assertStringStartsWith('Admin User on', $entityOne->getProvenance());

        $entityOne->setBody('t2');

        // There is no way to set Created, so use Reflection to violate encapsulation
        // Think I'm ok with this. Better than having the wrong visibility in the subject code.
        $noteClass = new ReflectionClass($entityOne);//'Fitch\TutorBundle\Entity\Note');
        $createdProperty = $noteClass->getProperty('created');
        $createdProperty->setAccessible(true);
        $createdProperty->setValue($entityOne, $entityOne->getCreated()->sub(new \DateInterval('P1Y')));

        $this->getModelManager()->saveEntity($entityOne);

        $this->assertStringStartsWith('(Edited', $entityOne->getProvenance());

        $entityThree = $this->getModelManager()->findById(3);
        $this->assertStringStartsWith('Anonymous on', $entityThree->getProvenance());
    }

    /**
     *
     */
    public function testAuthor()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $entityThree = $this->getModelManager()->findById(3);

        $this->assertEquals('terms', $entityOne->getKey());

        $this->assertNotEquals($entityOne->getAuthor(), $entityThree->getAuthor());

        $entityThree->setAuthor($entityOne->getAuthor());
        $this->getModelManager()->saveEntity($entityThree);

        $this->assertEquals($entityOne->getAuthor(), $entityThree->getAuthor());
    }

    /**
     * @return NoteManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.note');
    }
}
