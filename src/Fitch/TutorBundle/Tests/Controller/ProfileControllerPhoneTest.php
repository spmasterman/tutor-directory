<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Entity\Phone;
use Fitch\TutorBundle\Model\CountryManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;

/**
 * Class ProfileControllerPhoneTest.
 */
class ProfileControllerPhoneTest extends FixturesWebTestCase
{
    use ProfileMockedUpdateTrait;

    /**
     * Test adding a new Phone Number.
     */
    public function testAddPhone()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // we need a country to test with
        $countryOne = $this->getCountryManager()->findById(1);

        // remove any existing numbers
        foreach ($tutor->getPhoneNumbers() as $existingNumber) {
            $tutor->removePhoneNumber($existingNumber);
        };

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->performMockedUpdate($tutor, 'phone0', [
            'phonePk' => 0,
            'value' => [
                'number' => TestSlug::END_1,
                'type' => TestSlug::END_2,
                'isPreferred' => "true",
                'country' => $countryOne->getId(),
            ],
        ]);

        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::END_1, $tutor->getPhoneNumbers()->first()->getNumber());
        $this->assertEquals(TestSlug::END_2, $tutor->getPhoneNumbers()->first()->getType());
        $this->assertEquals(true, $tutor->getPhoneNumbers()->first()->isPreferred());
        $this->assertEquals($countryOne, $tutor->getPhoneNumbers()->first()->getCountry());
    }

    /**
     * Test editing a Phone Number.
     */
    public function testUpdatePhone()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // we need a couple of countries to test with
        $countryOne = $this->getCountryManager()->findById(1);
        $countryTwo = $this->getCountryManager()->findById(2);

        // Set the address on his first competency, to the START tag
        /** @var Phone $phoneNumber*/
        $phoneNumber = $tutor->getPhoneNumbers()->first();
        $phoneNumber
            ->setNumber(TestSlug::START_1)
            ->setType(TestSlug::START_2)
            ->setPreferred(false)
            ->setCountry($countryOne)
        ;

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->assertEquals(TestSlug::START_1, $tutor->getPhoneNumbers()->first()->getNumber());
        $this->assertEquals(TestSlug::START_2, $tutor->getPhoneNumbers()->first()->getType());
        $this->assertEquals(false, $tutor->getPhoneNumbers()->first()->isPreferred());
        $this->assertEquals($countryOne, $tutor->getPhoneNumbers()->first()->getCountry());

        $this->performMockedUpdate($tutor, 'phone'.$phoneNumber->getId(), [
            'phonePk' => $phoneNumber->getId(),
            'value' => [
                'number' => TestSlug::END_1,
                'type' => TestSlug::END_2,
                'isPreferred' => "true",
                'country' => $countryTwo->getId(),
            ],
        ]);

        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::END_1, $tutor->getPhoneNumbers()->first()->getNumber());
        $this->assertEquals(TestSlug::END_2, $tutor->getPhoneNumbers()->first()->getType());
        $this->assertEquals(true, $tutor->getPhoneNumbers()->first()->isPreferred());
        $this->assertEquals($countryTwo, $tutor->getPhoneNumbers()->first()->getCountry());
    }

    /**
     * NOTE: Removing a Phone Number tested in PhoneController.
     */

    /**
     * @return TutorManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }

    /**
     * @return CountryManagerInterface
     */
    public function getCountryManager()
    {
        return $this->container->get('fitch.manager.country');
    }
}
