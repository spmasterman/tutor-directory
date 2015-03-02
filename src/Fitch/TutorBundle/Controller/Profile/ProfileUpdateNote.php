<?php

namespace Fitch\TutorBundle\Controller\Profile;

use Fitch\CommonBundle\Model\UserCallableInterface;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\NoteManagerInterface;
use Fitch\TutorBundle\Model\Traits\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;

class ProfileUpdateNote implements ProfileUpdateInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    public function update(Request $request, Tutor $tutor, $value)
    {
        $noteId = $request->request->get('notePk');
        if ($noteId) {
            $note = $this->getNoteManager()->findById($noteId);
        } else {
            $note = $this->getNoteManager()->createEntity();
            $note->setKey($request->request->get('noteKey'))
            ;
            $tutor->addNote($note);
        }

        // only update things if they have changed
        if ($value != $note->getBody()) {
            $note
                ->setAuthor($this->getUserCallable()->getCurrentUser())
                ->setBody($value)
            ;
        }

        return $note;
    }

    /**
     * @return UserCallableInterface
     */
    private function getUserCallable()
    {
        return $this->container->get('fitch.user_callable');
    }

    /**
     * @return NoteManagerInterface
     */
    private function getNoteManager()
    {
        return $this->container->get('fitch.manager.note');
    }
}
