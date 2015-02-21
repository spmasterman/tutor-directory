<?php

namespace Fitch\CommonBundle\Tests;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class FixturesWebTestCase extends WebTestCase
{
    /**
     * @var string
     */
    protected $environment = 'test';

    /**
     * @var boolean
     */
    protected $debug = true;

    /**
     * @var Container
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param string|null $name     Test name
     * @param array       $data     Test data
     * @param string      $dataName Data name
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        if (!static::$kernel) {
            static::$kernel = self::createKernel([
                'environment' => $this->environment,
                'debug'       => $this->debug,
            ]);
            static::$kernel->boot();
        }

        $this->container = static::$kernel->getContainer();
    }

    protected function setUp()
    {
        restoreDatabase();
    }

    protected function tearDown()
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();
        $em->clear();
    }
}
