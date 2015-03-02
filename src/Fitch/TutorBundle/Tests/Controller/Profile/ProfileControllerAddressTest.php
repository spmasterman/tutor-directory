<?php

namespace Fitch\TutorBundle\Tests\Controller\Profile;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Entity\Address;
use Fitch\TutorBundle\Model\CountryManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Fitch\TutorBundle\Tests\Controller\ProfileMockedUpdateTrait;
use Fitch\TutorBundle\Tests\Controller\TestSlug;

/**
 * Class ProfileControllerAddressTest.
 */
class ProfileControllerAddressTest extends FixturesWebTestCase
{
    use ProfileMockedUpdateTrait;

    /**
     * Test adding a new Address.
     */
    public function testAddAddress()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // we need a country to test with
        $countryOne = $this->getCountryManager()->findById(1);

        // remove any existing numbers
        foreach ($tutor->getAddresses() as $existingAddress) {
            $tutor->removeAddress($existingAddress);
        };

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->performMockedUpdate($tutor, 'address0', [
            'addressPk' => 0,
            'value' => [
                'type' => TestSlug::END_1,
                'streetPrimary' => TestSlug::END_2,
                'streetSecondary' => TestSlug::END_3,
                'city' => TestSlug::END_4,
                'state' => TestSlug::END_5,
                'zip' => TestSlug::END_6,
                'country' => $countryOne->getId(),
            ],
        ]);

        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::END_1, $tutor->getAddresses()->first()->getType());
        $this->assertEquals(TestSlug::END_2, $tutor->getAddresses()->first()->getStreetPrimary());
        $this->assertEquals(TestSlug::END_3, $tutor->getAddresses()->first()->getStreetSecondary());
        $this->assertEquals(TestSlug::END_4, $tutor->getAddresses()->first()->getCity());
        $this->assertEquals(TestSlug::END_5, $tutor->getAddresses()->first()->getState());
        $this->assertEquals(TestSlug::END_6, $tutor->getAddresses()->first()->getZip());
        $this->assertEquals($countryOne, $tutor->getAddresses()->first()->getCountry());
    }

    /**
     * Test editing a Address.
     */
    public function testUpdateAddress()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // we need a couple of countries to test with
        $countryOne = $this->getCountryManager()->findById(1);
        $countryTwo = $this->getCountryManager()->findById(2);

        // Set the address on his first competency, to the START tag
        /** @var Address $address*/
        $address = $tutor->getAddresses()->first();
        $address
            ->setType(TestSlug::START_1)
            ->setStreetPrimary(TestSlug::START_2)
            ->setStreetSecondary(TestSlug::START_3)
            ->setCity(TestSlug::START_4)
            ->setState(TestSlug::START_5)
            ->setZip(TestSlug::START_6)
            ->setCountry($countryOne)
        ;

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->assertEquals(TestSlug::START_1, $tutor->getAddresses()->first()->getType());
        $this->assertEquals(TestSlug::START_2, $tutor->getAddresses()->first()->getStreetPrimary());
        $this->assertEquals(TestSlug::START_3, $tutor->getAddresses()->first()->getStreetSecondary());
        $this->assertEquals(TestSlug::START_4, $tutor->getAddresses()->first()->getCity());
        $this->assertEquals(TestSlug::START_5, $tutor->getAddresses()->first()->getState());
        $this->assertEquals(TestSlug::START_6, $tutor->getAddresses()->first()->getZip());
        $this->assertEquals($countryOne, $tutor->getAddresses()->first()->getCountry());

        $this->performMockedUpdate($tutor, 'address'.$address->getId(), [
            'addressPk' => $address->getId(),
            'value' => [
                'type' => TestSlug::END_1,
                'streetPrimary' => TestSlug::END_2,
                'streetSecondary' => TestSlug::END_3,
                'city' => TestSlug::END_4,
                'state' => TestSlug::END_5,
                'zip' => TestSlug::END_6,
                'country' => $countryTwo->getId(),
            ],
        ]);

        $manager->reloadEntity($tutor);

        $this->assertEquals(TestSlug::END_1, $tutor->getAddresses()->first()->getType());
        $this->assertEquals(TestSlug::END_2, $tutor->getAddresses()->first()->getStreetPrimary());
        $this->assertEquals(TestSlug::END_3, $tutor->getAddresses()->first()->getStreetSecondary());
        $this->assertEquals(TestSlug::END_4, $tutor->getAddresses()->first()->getCity());
        $this->assertEquals(TestSlug::END_5, $tutor->getAddresses()->first()->getState());
        $this->assertEquals(TestSlug::END_6, $tutor->getAddresses()->first()->getZip());
        $this->assertEquals($countryTwo, $tutor->getAddresses()->first()->getCountry());
    }

    /**
     * NOTE: Removing a Address Number tested in AddressController.
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
