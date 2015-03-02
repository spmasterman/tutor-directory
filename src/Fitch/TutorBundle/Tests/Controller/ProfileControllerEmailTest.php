<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Controller\ProfileController;
use Fitch\TutorBundle\Entity\Competency;
use Fitch\TutorBundle\Entity\Email;
use Fitch\TutorBundle\Entity\Phone;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\CountryManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class ProfileControllerEmailTest.
 */
class ProfileControllerEmailTest extends FixturesWebTestCase
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
     * Test editing an email.
     */
    public function testUpdatingEmail()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // Set the address on his first competency, to the START tag
        /** @var Email $email*/
        $email = $tutor->getEmailAddresses()->first();
        $email
            ->setAddress(self::START_1)
            ->setType(self::START_2)
        ;

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(self::START_1, $tutor->getEmailAddresses()->first()->getAddress());
        $this->assertEquals(self::START_2, $tutor->getEmailAddresses()->first()->getType());

        $this->performMockedUpdate($tutor, 'email'.$email->getId(), [
            'emailPk' => $email->getId(),
            'value' => [
                'address' => self::END_1,
                'type' => self::END_2,
            ],
        ]);

        $manager->reloadEntity($tutor);
        $this->assertEquals(self::END_1, $tutor->getEmailAddresses()->first()->getAddress());
        $this->assertEquals(self::END_2, $tutor->getEmailAddresses()->first()->getType());
    }

    /**
     * @return TutorManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }
}
