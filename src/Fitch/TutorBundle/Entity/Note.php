<?php

namespace Fitch\TutorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fitch\CommonBundle\Entity\IdentityEntityTrait;
use Fitch\CommonBundle\Entity\IdentityEntityInterface;
use Fitch\CommonBundle\Entity\TimestampableEntityTrait;
use Fitch\CommonBundle\Entity\TimestampableEntityInterface;
use Fitch\UserBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;

/**
 * Phone.
 *
 * @ORM\Table(name="note")
 * @ORM\Entity(repositoryClass="Fitch\TutorBundle\Entity\Repository\NoteRepository")
 */
class Note implements
    IdentityEntityInterface,
    TimestampableEntityInterface
{
    use IdentityEntityTrait, TimestampableEntityTrait, ProvenanceBuilderTrait;

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
    protected $key;

    /**
     * @ORM\Column(name="body", type="text")
     *
     * @var string
     */
    protected $body;

    /**
     * @ORM\ManyToOne(targetEntity="Fitch\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @var User
     */
    protected $author;

    /**
     * @return Tutor
     */
    public function getTutor()
    {
        return $this->tutor;
    }

    /**
     * @param Tutor $tutor
     *
     * @return $this
     */
    public function setTutor($tutor)
    {
        $this->tutor = $tutor;

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
     *
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
     *
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
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get the author created/edited string
     *
     * @return string
     */
    public function getProvenance()
    {
        return $this->generateProvenanceString($this->getAuthor());
    }
}
