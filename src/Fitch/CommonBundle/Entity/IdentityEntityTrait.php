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
    protected $id;

    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
