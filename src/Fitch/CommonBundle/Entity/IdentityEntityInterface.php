<?php

namespace Fitch\CommonBundle\Entity;

/**
 * Interface IdentityEntityInterface.
 */
interface IdentityEntityInterface
{
    /**
     * Get id.
     *
     * @return integer
     */
    public function getId();

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function setId($id);
}
