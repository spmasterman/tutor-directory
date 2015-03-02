<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\CurrencyManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;

/**
 * Class ProfileControllerCurrencyTest.
 */
class ProfileControllerCurrencyTest extends FixturesWebTestCase
{
    use ProfileMockedUpdateTrait;

    /**
     * Test editing a Currency
     */
    public function testUpdateCurrency()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // we need a couple of countries to test with
        $currencyOne = $this->getCurrencyManager()->findById(1);
        $currencyTwo = $this->getCurrencyManager()->findById(2);

        // Set the currency to Currency One

        $tutor->setCurrency($currencyOne);
        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->assertEquals($currencyOne, $tutor->getCurrency());

        $this->performMockedUpdate($tutor, 'currency', [
            'value' => $currencyTwo->getId(),
        ]);

        $manager->reloadEntity($tutor);

        $this->assertEquals($currencyTwo, $tutor->getCurrency());
    }

    /**
     * @return TutorManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }

    /**
     * @return CurrencyManagerInterface
     */
    public function getCurrencyManager()
    {
        return $this->container->get('fitch.manager.currency');
    }
}
