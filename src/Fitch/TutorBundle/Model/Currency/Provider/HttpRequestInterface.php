<?php

namespace Fitch\TutorBundle\Model\Currency\Provider;

interface HttpRequestInterface
{
    public function setOption($name, $value);
    public function execute();
    public function getInfo($name);
    public function close();
}
