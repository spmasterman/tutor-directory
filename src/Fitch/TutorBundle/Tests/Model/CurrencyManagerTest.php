<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\CommonBundle\Tests\Model\ChoicesModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\DefaultableModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\FindModelManagerTestTrait;
use Fitch\CommonBundle\Tests\Model\TimestampableModelManagerTestTrait;
use Fitch\TutorBundle\Entity\Currency;
use Fitch\TutorBundle\Model\Currency\Provider\ProviderInterface;
use Fitch\TutorBundle\Model\CurrencyManagerInterface;

class CurrencyManagerTest extends FixturesWebTestCase
{
    use TimestampableModelManagerTestTrait,
        DefaultableModelManagerTestTrait,
        ChoicesModelManagerTestTrait,
        FindModelManagerTestTrait;

    const FIXTURE_COUNT = 4;
    const ACTIVE_FIXTURE_COUNT = 2;
    const PREFERRED_FIXTURE_COUNT = 2;
    const ACTIVE_AND_PREFERRED_COUNT = 1;

    /** @var  CurrencyManagerInterface */
    protected $modelManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.currency');
    }

    /**
     * Tests the findAll.
     */
    public function testFindAll()
    {
        $this->performFindAllTest(self::FIXTURE_COUNT, 'ONE - Test Currency One', function (Currency $entity) {
            return (string) $entity;
        });
    }

    /**
     *
     */
    public function testFindAllSorted()
    {
        $sorted = $this->modelManager->findAllSorted();

        $index = 0;
        foreach ($sorted as $entity) {
            if ($index < self::PREFERRED_FIXTURE_COUNT) {
                $this->assertTrue($entity->isPreferred(), 'Unexpected Not-Preferred Entity');
            } else {
                $this->assertFalse($entity->isPreferred(), 'Unexpected Preferred Entity');
            }

            $index++;
        }
    }

    /**
     *
     */
    public function testFindById()
    {
        $this->performFindByIdTest(1, 'Test Currency One', function (Currency $entity) {
            return $entity->getName();
        });
    }

    /**
     *
     */
    public function testLifeCycle()
    {
        $this->performLifeCycleTests(
            self::FIXTURE_COUNT,
            function (Currency $entity) {
                $entity
                    ->setName('c')
                    ->setPreferred(true)
                    ->setActive(false)
                    ->setThreeDigitCode('123')
                    ->setToGBP(1)
                ;
            },
            function (Currency $entity) {
                $entity->setName('p2');
            },
            function (Currency $entity) {
                $entity->setName('p3');
            },
            function (Currency $entity) {
                return (bool) 'p2' == $entity->getName();
            }
        );
    }

    /**
     *
     */
    public function testBuildChoices()
    {
        $this->performBuildChoicesTest(
            self::ACTIVE_FIXTURE_COUNT,
            function ($entity) {
                return $entity instanceof Currency;
            },
            function (Currency $entity) {
                return $entity->isActive();
            }
        );
    }

    /**
     *
     */
    public function testBuildPreferredChoices()
    {
        $this->performBuildPreferredChoicesTest(
            self::ACTIVE_AND_PREFERRED_COUNT,
            function ($entity) {
                return $entity instanceof Currency;
            }
        );
    }

    /**
     *
     */
    public function testFindDefault()
    {
        // Should fail, nothing in the fixture matches the default
        $this->performFindDefaultTest(
            function ($entity) {
                return is_null($entity);
            },
            function ($entity) {
                return is_null($entity);
            }
        );

        // fix that
        $defaultEntity = $this->modelManager->findById(1);
        $defaultEntity->setThreeDigitCode('GBP');
        $this->modelManager->saveEntity($defaultEntity);

        // Should work
        $this->performFindDefaultTest(
            function ($entity) {
                return $entity instanceof Currency;
            },
            function (Currency $entity) {
                return $entity->getThreeDigitCode() == 'GBP';
            }
        );
    }

    /**
     *
     */
    public function testExchangeRate()
    {
        $entity = $this->modelManager->findById(1);
        $newExchangeRate = $this->modelManager->updateExchangeRate(
            $this->getMockProviderExpectingToBeCalled(1, 1.123),
            $entity
        );
        $this->assertEquals(1.123, $newExchangeRate);

        // Need to set RateUpdated to null, or a week old really to test the "selection" of a
        // currency
        foreach ($this->modelManager->findAll() as $entity) {
            $entity->setRateUpdated($entity->getCreated());
            $this->modelManager->saveEntity($entity);
        }

        // Nothing is required at this stage:
        $this->assertTrue(
            $this->modelManager->performExchangeRateUpdateIfRequired(
                $this->getMockProviderExpectingToBeCalled(0, false)
            )
        );

        // Set up something that looks like it needs an update
        $entity->setRateUpdated(null);
        $entity->setToGBP(0);
        $this->modelManager->saveEntity($entity);

        // The provider should get called - one time
        $this->assertTrue(
            $this->modelManager->performExchangeRateUpdateIfRequired(
                $this->getMockProviderExpectingToBeCalled(1, 1.123)
            )
        );

        // and the value should be updated
        $this->assertEquals(1.123, $entity->getToGBP());

        //do it again but fake the provider failing
        $entity->setRateUpdated(null);
        $entity->setToGBP(0);
        $this->modelManager->saveEntity($entity);

        // The provider should get called - one time
        $this->assertFalse(
            $this->modelManager->performExchangeRateUpdateIfRequired(
                $this->getMockProviderExpectingToBeCalled(1, false)
            )
        );

        // and the value should not be updated
        $this->assertEquals(0, $entity->getToGBP());

    }

    /**
     * @param $times
     * @return ProviderInterface
     */
    private function getMockProviderExpectingToBeCalled($times, $returning)
    {
        $mockProvider = $this
            ->getMockBuilder('Fitch\TutorBundle\Model\Currency\Provider\ProviderInterface')
            ->getMock();
        $mockProvider->expects($this->exactly($times))->method('getRate')->willReturn($returning);
        return $mockProvider;
    }
}
