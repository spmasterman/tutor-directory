<?php

namespace Fitch\CommonBundle\Entity;

interface IdentityTraitInterface
{
    /**
     * Get id
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
