<?php

namespace Fitch\TutorBundle\Tests\Controller\Profile;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\CurrencyManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Fitch\TutorBundle\Tests\Controller\ProfileMockedUpdateTrait;
use Fitch\TutorBundle\Tests\Controller\TestSlug;
use Fitch\UserBundle\Model\UserManagerInterface;

/**
 * Class ProfileControllerRateTest.
 */
class ProfileControllerRateTest extends FixturesWebTestCase
{
    use ProfileMockedUpdateTrait;

    /**
     * Test editing a Rate
     */
    public function testUpdateRate()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // Remove any existing notes
        foreach ($tutor->getRates() as $rate) {
            $tutor->removeRate($rate);
        };

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        // Add a new note

        $this->performMockedUpdate($tutor, 'rate0', [
            'ratePk' => 0,
            'value' => [
                'name' => TestSlug::START_1,
                'amount' => 1
            ],
        ]);

        $manager->reloadEntity($tutor);

        $this->assertEquals(TestSlug::START_1, $tutor->getRates()->first()->getName());
        $this->assertEquals(1, $tutor->getRates()->first()->getAmount());

        // Now edit the rate
        $rateId = $tutor->getRates()->first()->getId();

        $this->performMockedUpdate($tutor, 'rate'.$rateId, [
            'ratePk' => $rateId,
            'value' => [
                'name' => TestSlug::END_1,
                'amount' => 2
            ],
        ]);

        $this->assertEquals(TestSlug::END_1, $tutor->getRates()->first()->getName()); //NOTE: KEY shouldn't change
        $this->assertEquals(2, $tutor->getRates()->first()->getAmount());
    }

    /**
     * @return TutorManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }
}
