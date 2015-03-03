<?php

namespace Fitch\CommonBundle\Model;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Validator;

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
            try {
                static::$kernel = self::createKernel([
                    'environment' => $this->environment,
                    'debug'       => $this->debug,
                ]);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
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
