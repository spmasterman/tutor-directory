<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityTrait;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Entity\TimestampableTrait;
use Fitch\CommonBundle\Entity\TimestampableTraitInterface;
use Fitch\UserBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Phone
 *
 * @ORM\Table(name="note")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\NoteRepository")
 */
class Note implements IdentityTraitInterface, TimestampableTraitInterface
{
    use IdentityTrait, TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Tutor", inversedBy="notes")
     * @ORM\JoinColumn(name="tutor_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var Tutor
     */
    protected $tutor;

    /**
     * @ORM\Column(name="note_key", type="string", length=32)
     *
     * @var string
     */
    private $key;

    /**
     * @ORM\Column(name="body", type="text")
     *
     * @var string
     */
    private $body;

    /**
     * @ORM\ManyToOne(targetEntity="Fitch\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @var User
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="NoteVisibility")
     * @ORM\JoinColumn(name="note_visibility_id", referencedColumnName="id")
     *
     * @var NoteVisibility
     */
    private $visibility;

    /**
     * @return Tutor
     */
    public function getTutor()
    {
        return $this->tutor;
    }

    /**
     * @param Tutor $tutor
     * @return $this
     */
    public function setTutor($tutor)
    {
        $this->tutor = $tutor;
        return $this;
    }

    /**
     * @return NoteVisibility
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @param NoteVisibility $visibility
     * @return $this
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function getProvenance()
    {
        $author = $this->getAuthor();
        if ($author) {
            $fullName = $author->getFullName();
            $string = $fullName ? $fullName : $author->getUsername();
        } else {
            $string = 'Anonymous';
        }

        $string .= ' on ' . $this->getCreated()->format('M d, Y');

        if ($this->getUpdated() != $this->getCreated()) {
            $string = '(Edited ' . $this->getUpdated()->format('M d, Y') . ') '. $string;
        }
        return $string;
    }
}
