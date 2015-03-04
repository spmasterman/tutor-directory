<?php

namespace Fitch\TutorBundle\Tests\Controller\Profile;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Fitch\TutorBundle\Tests\Controller\ProfileMockedUpdateTrait;
use Fitch\TutorBundle\Tests\Controller\TestSlug;
use Fitch\UserBundle\Model\UserManagerInterface;

/**
 * Class ProfileControllerNoteTest.
 */
class ProfileControllerNoteTest extends FixturesWebTestCase
{
    use ProfileMockedUpdateTrait;

    private $savedService;

    /**
     * Test editing a Note
     */
    public function testUpdateNote()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // Remove any existing notes
        foreach ($tutor->getNotes() as $note) {
            $tutor->removeNote($note);
        };

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        // Add a new note
        $this->injectMockUserService(5); // Super Admin User

        $this->performMockedUpdate($tutor, 'note0', [
            'notePk' => 0,
            'noteKey' => TestSlug::START_1,
            'value' => TestSlug::START_2,
        ]);

        $manager->reloadEntity($tutor);

        $this->assertEquals(TestSlug::START_1, $tutor->getNotes()->first()->getKey());
        $this->assertEquals(TestSlug::START_2, $tutor->getNotes()->first()->getBody());
        $this->assertEquals('Super Admin User', $tutor->getNotes()->first()->getAuthor()->getFullName());

        // Now edit the note
        $noteId = $tutor->getNotes()->first()->getId();
        $this->injectMockUserService(4); // NOT Super Admin User - Admin User

        $this->performMockedUpdate($tutor, 'note'.$noteId, [
            'notePk' => $noteId,
            'noteKey' => TestSlug::END_1,
            'value' => TestSlug::END_2,
        ]);

        $this->assertEquals(TestSlug::START_1, $tutor->getNotes()->first()->getKey()); //NOTE: KEY shouldn't change
        $this->assertEquals(TestSlug::END_2, $tutor->getNotes()->first()->getBody());
        $this->assertEquals('Admin User', $tutor->getNotes()->first()->getAuthor()->getFullName());

        // now resubmit unchanged data with a new user - the author shouldn't change, this simulates clicking on the
        // edit, click save, but nat actually changing anything. Note attribution/provenance should only change if
        // you make an edit.
        $this->injectMockUserService(3); // NOT Admin User - Editor User

        $this->performMockedUpdate($tutor, 'note'.$noteId, [
            'notePk' => $noteId,
            'value' => TestSlug::END_2,
        ]);

        $this->assertEquals(TestSlug::START_1, $tutor->getNotes()->first()->getKey()); //NOTE: KEY shouldn't change
        $this->assertEquals(TestSlug::END_2, $tutor->getNotes()->first()->getBody());
        $this->assertEquals('Admin User', $tutor->getNotes()->first()->getAuthor()->getFullName());

        // we messed with the container - which is held as a static in these tests - invalidate it for next test
        $this->restoreContainer();
    }

    /**
     * This injects a mock into the container in place of the UserCallable service (which is used to get the
     * current user in client classes). The mock actually returns a real user.
     *
     * @param $id
     */
    private function injectMockUserService($id)
    {
        $mockUserCallable = $this->getMockBuilder('Fitch\CommonBundle\Model\UserCallableInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mockUserCallable->expects($this->any())->method('getCurrentUser')->willReturn(
            $this->getUserManager()->findById($id)
        );
        $this->savedService = $this->container->get('fitch.user_callable');
        $this->container->set('fitch.user_callable', $mockUserCallable);
    }

    private function restoreContainer()
    {
        $this->container->set('fitch.user_callable', $this->savedService);
    }

    /**
     * @return TutorManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }
}
