<?php

namespace Fitch\TutorBundle\Model\Traits;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait ContainerAwareTrait
{
    /** @var ContainerInterface $container */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
