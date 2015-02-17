<?php

namespace Fitch\TutorBundle\Command;

use Fitch\TutorBundle\Model\Currency\Provider\YahooApi;
use Fitch\TutorBundle\Model\CurrencyManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UpdateExchangeRateCommand extends ContainerAwareCommand
{

    /** @var  ContainerInterface */
    protected $container;

    protected function configure()
    {
        $this
            ->setName('fitch:exchange-rate:update')
            ->setDescription('Update the oldest out-of-date exchange rate')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->container = $this->getContainer();
        $currencyManager = $this->getCurrencyManager();

        if ($currencyManager->performExchangeRateUpdateIfRequired(new YahooApi())) {
            $output->writeln(
                '<info>Exchange rate update ran without error</info>'
            );
        } else {
            $output->writeln('<error>Errors were detected updating the exchange rates.</error>');
        }
        return 0;
    }

    /**
     * @return CurrencyManager
     */
    private function getCurrencyManager()
    {
        return $this->container->get('fitch.manager.currency');
    }
}
