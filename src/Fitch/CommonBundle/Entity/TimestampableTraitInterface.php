<?php

namespace Fitch\CommonBundle\Entity;

use DateTime;

interface TimestampableTraitInterface
{
    /**
     * @return DateTime
     */
    public function getCreated();

    /**
     * @return DateTime
     */
    public function getUpdated();

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return mixed
     */
    public function setCreated($created);

    /**
     * Set updated.
     *
     * @param \DateTime $updated
     *
     * @return mixed
     */
    public function setUpdated($updated);
}
