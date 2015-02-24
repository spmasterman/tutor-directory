<?php

namespace Fitch\TutorBundle\Model;

use Doctrine\ORM\NoResultException;
use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\CommonBundle\Model\BaseModelManager;
use Fitch\TutorBundle\Entity\Repository\CurrencyRepository;
use Fitch\TutorBundle\Entity\Currency;
use Fitch\TutorBundle\Model\Currency\Provider\ProviderInterface;

class CurrencyManager extends BaseModelManager
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Currency
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * @return Currency[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @return Currency[]
     */
    public function findAllSorted()
    {
        return $this->getRepo()->findBy([], [
            'preferred' => 'DESC',
            'active' => 'DESC',
            'name' => 'ASC',
        ]);
    }

    /**
     * @return Currency
     */
    public function getDefaultCurrency()
    {
        return $this->getRepo()->findOneBy(['threeDigitCode' => 'GBP']);
    }

    /**
     * @return Currency[]
     */
    public function buildChoices()
    {
        return $this->getRepo()->findBy(['active' => true]);
    }

    /**
     * @return Currency[]
     */
    public function buildPreferredChoices()
    {
        return $this->getRepo()->findBy(['active' => true, 'preferred' => true]);
    }

    /**
     * @param Currency $currency
     * @param bool     $withFlush
     */
    public function saveCurrency($currency, $withFlush = true)
    {
        parent::saveEntity($currency, $withFlush);
    }

    /**
     * @param ProviderInterface $provider
     *
     * @return bool
     */
    public function performExchangeRateUpdateIfRequired(ProviderInterface $provider)
    {
        // Find the oldest currency exchange rate
        try {
            $currency = $this->getRepo()->getCandidateForExchangeRateUpdate(7 * 24); // a week old is fine
            if ($currency) {
                return $this->updateExchangeRate($provider, $currency);
            }
        } catch (NoResultException $e) {
            // do nothing
        }

        return true;
    }

    /**
     * @param ProviderInterface $provider
     * @param Currency          $currency
     *
     * @return bool
     */
    public function updateExchangeRate(ProviderInterface $provider, Currency $currency)
    {
        $rate = $provider->getRate($currency->getThreeDigitCode(), 'GBP');
        if (!$rate) {
            return false;
        }

        $currency->setToGBP($rate)->setRateUpdated(new \DateTime());
        $this->saveCurrency($currency);

        return true;
    }

    /**
     * Create a new Currency.
     *
     * Set its default values
     *
     * @return Currency
     */
    public function createCurrency()
    {
        return parent::createEntity();
    }

    /**
     * @param int $id
     */
    public function removeCurrency($id)
    {
        $currency = $this->findById($id);
        parent::removeEntity($currency);
    }

    /**
     * @param Currency $currency
     */
    public function refreshCurrency(Currency $currency)
    {
        parent::reloadEntity($currency);
    }

    /**
     * @return CurrencyRepository
     */
    private function getRepo()
    {
        return $this->repo;
    }

    /**
     * Used  to identify logs generated by this class.
     *
     * @return string
     */
    protected function getDebugKey()
    {
        return 'fitch.manager.currency';
    }
}
