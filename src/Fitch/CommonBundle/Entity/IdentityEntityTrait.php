<?php

namespace Fitch\CommonBundle\Entity;

/**
 * Class IdentityEntityTrait.
 */
trait IdentityEntityTrait
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
