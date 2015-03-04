<?php

namespace Fitch\CommonBundle\Model;

use Doctrine\ORM\EntityManager;
use Fitch\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class FixturesWebTestCase
 */
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

    /** @var  TokenStorageInterface */
    private $savedService;

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

    /**
     * Executed before very test
     */
    protected function setUp()
    {
        restoreDatabase();
    }

    /**
     * Executed after every test
     */
    protected function tearDown()
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();
        $em->clear();
    }

    /**
     * Executes a block/callable with the container setup to return the user
     * specified by $userId.
     *
     * @param int      $userId
     * @param callable $function
     */
    protected function performTestAsUser($userId, $function)
    {
        try {
            $this->injectMockUser($userId);
            $function();
        } finally {
            $this->restoreContainer();
        }
    }

    /**
     * @param int $id
     */
    private function injectMockUser($id)
    {
        $mockTokenStorage = $this->getMockBuilder(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockToken = $this->getMockBuilder(
            'Symfony\Component\Security\Core\Authentication\Token\TokenInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $user = $this->getUserManager()->findById($id);

        $mockToken->expects($this->any())->method('getUser')->willReturn($user);
        $mockTokenStorage->expects($this->any())->method('getToken')->willReturn($mockToken);

        $this->savedService = $this->container->get('security.token_storage');
        $this->container->set('security.token_storage', $mockTokenStorage);
    }

    /**
     * Puts the container back to its unadulterated state
     */
    private function restoreContainer()
    {
        $this->container->set('security.token_storage', $this->savedService);
    }

    /**
     * @return UserManagerInterface
     */
    protected function getUserManager()
    {
        return $this->container->get('fitch.manager.user');
    }
}
