<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\NoteRepository;
use Fitch\TutorBundle\Entity\Note;

class NoteManager extends BaseModelManager implements NoteManagerInterface
{
    /**
     * @param int $id
     */
    public function removeEntity($id)
    {
        $note = $this->findById($id);
        parent::removeEntity($note);
    }

    /**
     * Used  to identify logs generated by this class.
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.note';
    }
}
