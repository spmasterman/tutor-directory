<?php

namespace Fitch\TutorBundle\Tests\Entity;

use DateInterval;
use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\NoteManager;

class NoteTest extends FixturesWebTestCase
{
    public function testProvenance()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals('Test Note One', $entityOne->getBody());
        $this->assertStringStartsWith('Admin User on', $entityOne->getProvenance());

        $entityOne->setBody('t2');
        $entityOne->setCreated($entityOne->getCreated()->sub(new \DateInterval('P1Y')));
        $this->getModelManager()->saveNote($entityOne);

        $this->assertStringStartsWith('(Edited', $entityOne->getProvenance());

        $entityOne = $this->getModelManager()->findById(3);
        $this->assertStringStartsWith('Anonymous on', $entityOne->getProvenance());


    }

    public function tearDown()
    {

    }

    /**
     * @return NoteManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.note');
    }
}
