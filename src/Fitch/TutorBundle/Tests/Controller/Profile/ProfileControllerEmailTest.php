<?php

namespace Fitch\TutorBundle\Tests\Controller\Profile;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Entity\Email;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Fitch\TutorBundle\Tests\Controller\ProfileMockedUpdateTrait;
use Fitch\TutorBundle\Tests\Controller\TestSlug;

/**
 * Class ProfileControllerEmailTest.
 */
class ProfileControllerEmailTest extends FixturesWebTestCase
{
    use ProfileMockedUpdateTrait;

    /**
     * Test adding an email.
     */
    public function testUpdateEmail()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // Set the address on his first competency, to the START tag
        /** @var Email $email*/
        $email = $tutor->getEmailAddresses()->first();
        $email
            ->setAddress(TestSlug::START_1)
            ->setType(TestSlug::START_2)
        ;

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::START_1, $tutor->getEmailAddresses()->first()->getAddress());
        $this->assertEquals(TestSlug::START_2, $tutor->getEmailAddresses()->first()->getType());

        $this->performMockedUpdate($tutor, 'email'.$email->getId(), [
            'emailPk' => $email->getId(),
            'value' => [
                'address' => TestSlug::END_1,
                'type' => TestSlug::END_2,
            ],
        ]);

        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::END_1, $tutor->getEmailAddresses()->first()->getAddress());
        $this->assertEquals(TestSlug::END_2, $tutor->getEmailAddresses()->first()->getType());
    }

    /**
     * Test editing an email.
     */
    public function testAddEmail()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        // remove any existing emails
        foreach ($tutor->getEmailAddresses() as $emailAddress) {
            $tutor->removeEmailAddress($emailAddress);
        };

        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->performMockedUpdate($tutor, 'email0', [
            'emailPk' => 0,
            'value' => [
                'address' => TestSlug::END_1,
                'type' => TestSlug::END_2,
            ],
        ]);

        $manager->reloadEntity($tutor);
        $this->assertEquals(TestSlug::END_1, $tutor->getEmailAddresses()->first()->getAddress());
        $this->assertEquals(TestSlug::END_2, $tutor->getEmailAddresses()->first()->getType());
    }

    /**
     * NOTE: Removing a Email tested in EmailController.
     */

    /**
     * @return TutorManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }
}
