<?php

namespace Fitch\TutorBundle\Model;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\TutorBundle\Entity\Currency;
use Fitch\TutorBundle\Model\Currency\Provider\ProviderInterface;

interface CurrencyManagerInterface
{
    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     *
     * @return Currency
     */
    public function findById($id);

    /**
     * @return Currency[]
     */
    public function findAll();

    /**
     * @return Currency[]
     */
    public function findAllSorted();

    /**
     * @return Currency
     */
    public function findDefaultEntity();

    /**
     * @return Currency[]
     */
    public function buildChoices();

    /**
     * @return Currency[]
     */
    public function buildPreferredChoices();

    /**
     * @param Currency $currency
     * @param bool     $withFlush
     */
    public function saveEntity($currency, $withFlush = true);

    /**
     * @param ProviderInterface $provider
     *
     * @return bool
     */
    public function performExchangeRateUpdateIfRequired(ProviderInterface $provider);

    /**
     * @param ProviderInterface $provider
     * @param Currency          $currency
     *
     * @return bool
     */
    public function updateExchangeRate(ProviderInterface $provider, Currency $currency);

    /**
     * Create a new Currency.
     *
     * Set its default values
     *
     * @return Currency
     */
    public function createEntity();

    /**
     * @param Currency $entity
     * @param bool $withFlush
     */
    public function removeEntity($entity, $withFlush = true);

    /**
     * @param Currency $currency
     */
    public function reloadEntity($currency);
}
