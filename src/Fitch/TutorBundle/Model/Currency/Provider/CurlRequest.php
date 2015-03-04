<?php

namespace Fitch\TutorBundle\Model\Currency\Provider;

/**
 * Class CurlRequest.
 */
class CurlRequest implements HttpRequestInterface
{
    private $handle = null;

    /**
     * @param string|null $url
     */
    public function __construct($url = null)
    {
        $this->handle = curl_init($url);
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setOption($name, $value)
    {
        curl_setopt($this->handle, $name, $value);
    }

    /**
     * @return string|false
     */
    public function execute()
    {
        return curl_exec($this->handle);
    }

    /**
     * @param int $name
     *
     * @return mixed
     */
    public function getInfo($name)
    {
        return curl_getinfo($this->handle, $name);
    }

    /**
     *
     */
    public function close()
    {
        curl_close($this->handle);
    }
}
