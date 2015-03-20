<?php

namespace Fitch\CommonBundle\Entity;

use DateTime;

/**
 * Interface TimestampableEntityInterface.
 */
interface TimestampableEntityInterface
{
    /**
     * @return DateTime
     */
    public function getCreated();

    /**
     * @return DateTime
     */
    public function getUpdated();
}
