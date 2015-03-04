<?php

namespace Fitch\CommonBundle\Entity;

use Doctrine\Common\Inflector\Inflector;

/**
 * Class ArrayLoadableTrait.
 */
trait ArrayLoadableTrait
{
    /**
     * Loads up an object from a named array, via its setters.
     *
     * @param array $data
     */
    public function fromArray(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set'.Inflector::classify($key);

            if (method_exists($this, $method)) {
                call_user_func(array($this, $method), $value);
            }
        }
    }
}
