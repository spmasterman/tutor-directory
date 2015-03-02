<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Controller\ProfileController;
use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Entity\Phone;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\CountryManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class ProfileControllerTest.
 */
class ProfileControllerPhoneTest extends FixturesWebTestCase
{
    // Just some text constants to set values to and from in order to check that it happens right
    const START_1 = 'START_1';
    const START_2 = 'START_2';
    const END_1 = 'END_1';
    const END_2 = 'END_2';

    /**
     * @param Tutor  $tutor
     * @param string $name
     * @param string $value
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function performMockedUpdate(Tutor $tutor, $name, $requestExtras = [])
    {
        // Create a response payload that should change the Note
        $requestBag = new ParameterBag(array_merge([
            'pk' => $tutor->getId(),
            'name' => $name,
        ], $requestExtras));

        // Set that up in a Stub/mock Request
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $request->request = $requestBag;

        // Call the Controller Update
        $controller = new ProfileController();
        $controller->setContainer($this->container);

        return $controller->updateAction($request);
    }

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
                'number' => self::END_1,
                'type' => self::END_2,
                'isPreferred' => "true",
                'country' => $countryOne->getId(),
            ],
        ]);

        $manager->reloadEntity($tutor);
        $this->assertEquals(self::END_1, $tutor->getPhoneNumbers()->first()->getNumber());
        $this->assertEquals(self::END_2, $tutor->getPhoneNumbers()->first()->getType());
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
            ->setNumber(self::START_1)
            ->setType(self::START_2)
            ->setPreferred(false)
            ->setCountry($countryOne)
        ;

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->assertEquals(self::START_1, $tutor->getPhoneNumbers()->first()->getNumber());
        $this->assertEquals(self::START_2, $tutor->getPhoneNumbers()->first()->getType());
        $this->assertEquals(false, $tutor->getPhoneNumbers()->first()->isPreferred());
        $this->assertEquals($countryOne, $tutor->getPhoneNumbers()->first()->getCountry());

        $this->performMockedUpdate($tutor, 'phone'.$phoneNumber->getId(), [
            'phonePk' => $phoneNumber->getId(),
            'value' => [
                'number' => self::END_1,
                'type' => self::END_2,
                'isPreferred' => "true",
                'country' => $countryTwo->getId(),
            ],
        ]);

        $manager->reloadEntity($tutor);
        $this->assertEquals(self::END_1, $tutor->getPhoneNumbers()->first()->getNumber());
        $this->assertEquals(self::END_2, $tutor->getPhoneNumbers()->first()->getType());
        $this->assertEquals(true, $tutor->getPhoneNumbers()->first()->isPreferred());
        $this->assertEquals($countryTwo, $tutor->getPhoneNumbers()->first()->getCountry());
    }

    /**
     * NOTE: Removing a Phone Number tested in PhoneController
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
