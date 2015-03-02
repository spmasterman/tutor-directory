<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Controller\CompetencyController;
use Fitch\TutorBundle\Controller\CurrencyController;
use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Entity\Currency;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

/**
 * Class CurrencyExchangeRateControllerTest.
 */
class CurrencyExchangeRateControllerTest extends FixturesWebTestCase
{
    const PASS = true;
    const FAIL = false;

    public function testUpdateExchangeRatePassing()
    {
        $this->injectCurrencyManagerThatWill(self::PASS);

        $controller = new CurrencyController();
        $controller->setContainer($this->container);

        $controller->updateExchangeRateAction(new Currency());

        $flashes = $this->container->get('session')->getBag('flashes')->all();
        $this->assertCount(1, $flashes);
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);

        // we messed with the container - which is held as a static in these tests - invalidate it for next test
        $this->discardContainer();
    }

    public function testUpdateExchangeRateFailing()
    {
        $this->injectCurrencyManagerThatWill(self::FAIL);

        $controller = new CurrencyController();
        $controller->setContainer($this->container);

        $controller->updateExchangeRateAction(new Currency());

        $flashes = $this->container->get('session')->getBag('flashes')->all();
        $this->assertCount(1, $flashes);
        $this->assertArrayHasKey('warning', $flashes);
        $this->assertCount(1, $flashes['warning']);

        // we messed with the container - which is held as a static in these tests - invalidate it for next test
        $this->discardContainer();
    }


    private function injectCurrencyManagerThatWill($passOrFail)
    {
        $currencyManagerMock = $this->getMockBuilder('Fitch\TutorBundle\Model\CurrencyManager')
            ->disableOriginalConstructor()
            ->getMock();
        $currencyManagerMock->expects($this->any())->method('updateExchangeRate')->willReturn($passOrFail);
        $this->container->set('fitch.manager.currency', $currencyManagerMock);

    }
}
