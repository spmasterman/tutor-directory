<?php

namespace Fitch\TutorBundle\Tests\Command;

use Fitch\TutorBundle\Command\UpdateExchangeRateCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class UpdateExchangeRateCommandTest.
 */
class UpdateExchangeRateCommandTest extends WebTestCase
{
    const PASS = true;
    const FAIL = false;

    /** @var  UpdateExchangeRateCommand */
    protected $updateExchangeRateCommand;

    /** @var  Application */
    protected $application;

    /**
     * Boot a kernel and get an Application that can run commands. Create our Subject Under Test
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $this->application = new Application(static::$kernel);
        $this->updateExchangeRateCommand = new UpdateExchangeRateCommand();
    }

    /**
     * Use the Mock helpers to set u a container that will simulate a successful execution, check that we get the
     * correct response
     */
    public function testSuccessfulCommand()
    {
        /** @noinspection PhpParamsInspection */
        $this
            ->updateExchangeRateCommand
            ->setContainer(
                $this->getMockContainer(
                    $this->getMockCurrencyManagerThatWill(self::PASS)
                )
            );
        $this->application->add($this->updateExchangeRateCommand);

        $command = $this->application->find('fitch:exchange-rate:update');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertStringStartsWith('Exchange rate update ran without error', $commandTester->getDisplay());
    }

    /**
     * Use the Mock helpers to set u a container that will simulate an unsuccessful execution, check that we get the
     * correct response
     */
    public function testUnsuccessfulCommand()
    {
        /** @noinspection PhpParamsInspection */
        $this
            ->updateExchangeRateCommand
            ->setContainer(
                $this->getMockContainer(
                    $this->getMockCurrencyManagerThatWill(self::FAIL)
                )
            );
        $this->application->add($this->updateExchangeRateCommand);

        $command = $this->application->find('fitch:exchange-rate:update');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertStringStartsWith('Errors were detected', $commandTester->getDisplay());
    }

    /**
     * @param $passOrFail
     *
     * @return \PHPUnit_Framework_MockObject_Builder_InvocationMocker
     */
    private function getMockCurrencyManagerThatWill($passOrFail)
    {
        $currencyManager = $this
            ->getMockBuilder('Fitch\TutorBundle\Model\CurrencyManager')
            ->disableOriginalConstructor()
            ->getMock();

        $currencyManager
            ->expects($this->once())
            ->method('performExchangeRateUpdateIfRequired')
            ->willReturn($passOrFail);

        return $currencyManager;
    }

    /**
     * @param $currencyManager
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockContainer($currencyManager)
    {
        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $container->expects($this->once())->method('get')->willReturn($currencyManager);

        return $container;
    }
}
