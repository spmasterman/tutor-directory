<?php

namespace Fitch\TutorBundle\Model\Profile;

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
            $note
                ->setAuthor($this->getUser())
                ->setKey($request->request->get('noteKey'))
            ;
            $tutor->addNote($note);
        }
        $note->setBody($value);

        return $note;
    }

    /**
     * @return NoteManagerInterface
     */
    private function getNoteManager()
    {
        return $this->container->get('fitch.manager.note');
    }

    /**
     * Get a user from the Security Token Storage.
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see TokenInterface::getUser()
     */
    private function getUser()
    {
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }
    //// TODO - Get this from "UserCallable"
}
