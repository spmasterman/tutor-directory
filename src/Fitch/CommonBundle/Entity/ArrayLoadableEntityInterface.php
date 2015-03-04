<?php

namespace Fitch\CommonBundle\Entity;

/**
 * Interface ArrayLoadableEntityInterface.
 */
interface ArrayLoadableEntityInterface
{
    /**
     * Loads up an object from a named array, via its setters.
     *
     * @param array $data
     */
    public function fromArray(array $data);
}
