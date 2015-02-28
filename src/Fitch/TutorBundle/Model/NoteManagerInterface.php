<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Note;

interface NoteManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Note
     */
    public function findById($id);

    /**
     * @return Note[]
     */
    public function findAll();

    /**
     * @param Note $note
     * @param bool $withFlush
     */
    public function saveEntity($note, $withFlush = true);

    /**
     * Create a new Note.
     *
     * Set its default values
     *
     * @return Note
     */
    public function createNote();

    /**
     * @param int $id
     */
    public function removeNote($id);

    /**
     * @param Note $note
     */
    public function refreshNote(Note $note);
}
