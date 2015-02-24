<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\TutorManager;

class TutorTest extends FixturesWebTestCase
{
    /** @var  Tutor */
    protected $entityOne;
    /** @var  Tutor */
    protected $entityTwo;
    /** @var  Tutor */
    protected $entityThree;

    public function setUp()
    {
        parent::setUp();
        $this->entityOne = $this->getModelManager()->findById(1);
        $this->entityTwo = $this->getModelManager()->findById(2);
        $this->entityThree = $this->getModelManager()->findById(3);
    }

    public function testToString()
    {
        $this->assertEquals('Test Tutor One', $this->entityOne->getName());
    }

    public function testStatus()
    {
        $this->assertNotEquals($this->entityOne->getStatus()->getName(), $this->entityTwo->getStatus()->getName());
        $this->entityTwo->setStatus($this->entityOne->getStatus());
        $this->assertEquals($this->entityOne->getStatus()->getName(), $this->entityTwo->getStatus()->getName());
    }

    public function testTutorType()
    {
        $this->assertNotEquals($this->entityOne->getTutorType()->getName(), $this->entityTwo->getTutorType()->getName());
        $this->entityTwo->setTutorType($this->entityOne->getTutorType());
        $this->assertEquals($this->entityOne->getTutorType()->getName(), $this->entityTwo->getTutorType()->getName());
    }

    public function testCurrency()
    {
        $this->assertNotEquals($this->entityOne->getCurrency()->getName(), $this->entityTwo->getCurrency()->getName());
        $this->entityTwo->setCurrency($this->entityOne->getCurrency());
        $this->assertEquals($this->entityOne->getCurrency()->getName(), $this->entityTwo->getCurrency()->getName());
    }

    public function testBio()
    {
        $this->assertNotEquals($this->entityOne->getBio(), $this->entityTwo->getBio());
        $this->entityTwo->setBio($this->entityOne->getBio());
        $this->assertEquals($this->entityOne->getBio(), $this->entityTwo->getBio());
    }

    public function testOperatingRegion()
    {
        $this->assertNotEquals($this->entityOne->getRegion()->getName(), $this->entityTwo->getRegion()->getName());
        $this->entityTwo->setRegion($this->entityOne->getRegion());
        $this->assertEquals($this->entityOne->getRegion()->getName(), $this->entityTwo->getRegion()->getName());
    }

    public function testLinkedInURL()
    {
        $this->assertNotEquals($this->entityOne->getlinkedInURL(), $this->entityTwo->getlinkedInURL());
        $this->entityTwo->setlinkedInURL($this->entityOne->getlinkedInURL());
        $this->assertEquals($this->entityOne->getlinkedInURL(), $this->entityTwo->getlinkedInURL());
    }

    public function testAddresses()
    {
        $this->assertCount(2, $this->entityOne->getAddresses());
        $this->assertCount(1, $this->entityTwo->getAddresses());

        $this->assertEquals($this->entityTwo, $this->entityTwo->getAddresses()->first()->getTutor());

        // remove the address from #2
        $this->entityTwo->removeAddress($this->entityTwo->getAddresses()->first());
        $this->assertCount(0, $this->entityTwo->getAddresses());

        // add the addresses from #1 to #2
        foreach ($this->entityOne->getAddresses() as $address) {
            $this->entityTwo->addAddress($address);
        }

        // check that they are the same
        $this->assertEquals($this->entityOne->getAddresses()->count(), $this->entityTwo->getAddresses()->count());
        foreach ($this->entityTwo->getAddresses() as $address) {
            $this->assertContains($address, $this->entityOne->getAddresses());
        }

        // Add them a second time - they shouldn't get duplicated
        foreach ($this->entityOne->getAddresses() as $address) {
            $this->entityTwo->addAddress($address);
        }
        $this->assertEquals($this->entityOne->getAddresses()->count(), $this->entityTwo->getAddresses()->count());

        // Remove one of them
        $this->entityTwo->removeAddress($this->entityTwo->getAddresses()->first());
        $this->assertCount(1, $this->entityTwo->getAddresses());

        // now set them in bulk
        $this->entityTwo->setAddresses($this->entityOne->getAddresses());
        $this->assertEquals($this->entityOne->getAddresses()->count(), $this->entityTwo->getAddresses()->count());
        foreach ($this->entityTwo->getAddresses() as $address) {
            $this->assertContains($address, $this->entityOne->getAddresses());
        }
    }

    public function testPhoneNumbers()
    {
        $this->assertCount(2, $this->entityOne->getPhoneNumbers());
        $this->assertCount(1, $this->entityTwo->getPhoneNumbers());

        $this->assertEquals($this->entityTwo, $this->entityTwo->getPhoneNumbers()->first()->getTutor());

        // remove the phone number from #2
        $this->assertTrue($this->entityTwo->hasPhoneNumber());
        $this->entityTwo->removePhonenumber($this->entityTwo->getPhoneNumbers()->first());
        $this->assertCount(0, $this->entityTwo->getPhoneNumbers());
        $this->assertFalse($this->entityTwo->hasPhoneNumber());

        // add the phone numbers from #1 to #2
        foreach ($this->entityOne->getPhoneNumbers() as $phoneNumber) {
            $this->entityTwo->addPhoneNumber($phoneNumber);
        }

        // check that they are the same
        $this->assertEquals($this->entityOne->getPhoneNumbers()->count(), $this->entityTwo->getPhoneNumbers()->count());
        foreach ($this->entityTwo->getPhoneNumbers() as $phoneNumber) {
            $this->assertContains($phoneNumber, $this->entityOne->getPhoneNumbers());
        }

        // Add them a second time - they shouldn't get duplicated
        foreach ($this->entityOne->getPhoneNumbers() as $phoneNumber) {
            $this->entityTwo->addPhoneNumber($phoneNumber);
        }
        $this->assertEquals($this->entityOne->getPhoneNumbers()->count(), $this->entityTwo->getPhoneNumbers()->count());

        // Remove one of them
        $this->entityTwo->removePhoneNumber($this->entityTwo->getPhoneNumbers()->first());
        $this->assertCount(1, $this->entityTwo->getPhoneNumbers());

        // now set them in bulk
        $this->entityTwo->setPhonenumbers($this->entityOne->getPhoneNumbers());
        $this->assertEquals($this->entityOne->getPhoneNumbers()->count(), $this->entityTwo->getPhoneNumbers()->count());
        foreach ($this->entityTwo->getPhoneNumbers() as $phoneNumber) {
            $this->assertContains($phoneNumber, $this->entityOne->getPhoneNumbers());
        }
    }

    public function testRates()
    {
        $this->assertCount(2, $this->entityOne->getRates());
        $this->assertCount(1, $this->entityTwo->getRates());

        $this->assertEquals($this->entityTwo, $this->entityTwo->getRates()->first()->getTutor());

        // remove the rate from #2
        $this->entityTwo->removeRate($this->entityTwo->getRates()->first());
        $this->assertCount(0, $this->entityTwo->getRates());

        // add the rates from #1 to #2
        foreach ($this->entityOne->getRates() as $rate) {
            $this->entityTwo->addRate($rate);
        }

        // check that they are the same
        $this->assertEquals($this->entityOne->getRates()->count(), $this->entityTwo->getRates()->count());
        foreach ($this->entityTwo->getRates() as $rate) {
            $this->assertContains($rate, $this->entityOne->getRates());
        }

        // Add them a second time - they shouldn't get duplicated
        foreach ($this->entityOne->getRates() as $rate) {
            $this->entityTwo->addRate($rate);
        }
        $this->assertEquals($this->entityOne->getRates()->count(), $this->entityTwo->getRates()->count());

        // Remove one of them
        $this->entityTwo->removeRate($this->entityTwo->getRates()->first());
        $this->assertCount(1, $this->entityTwo->getRates());

        // now set them in bulk
        $this->entityTwo->setRates($this->entityOne->getRates());
        $this->assertEquals($this->entityOne->getRates()->count(), $this->entityTwo->getRates()->count());
        foreach ($this->entityTwo->getRates() as $rate) {
            $this->assertContains($rate, $this->entityOne->getRates());
        }
    }

    public function testEmailAddresses()
    {
        $this->assertCount(2, $this->entityOne->getEmailAddresses());
        $this->assertCount(1, $this->entityTwo->getEmailAddresses());

        $this->assertEquals($this->entityTwo, $this->entityTwo->getEmailAddresses()->first()->getTutor());

        // remove the email address from #2
        $this->entityTwo->removeEmailAddress($this->entityTwo->getEmailAddresses()->first());
        $this->assertCount(0, $this->entityTwo->getEmailAddresses());

        // add the email addresses numbers from #1 to #2
        foreach ($this->entityOne->getEmailAddresses() as $emailAddress) {
            $this->entityTwo->addEmailAddress($emailAddress);
        }

        // check that they are the same
        $this->assertEquals($this->entityOne->getEmailAddresses()->count(), $this->entityTwo->getEmailAddresses()->count());
        foreach ($this->entityTwo->getEmailAddresses() as $emailAddress) {
            $this->assertContains($emailAddress, $this->entityOne->getEmailAddresses());
        }

        // Add them a second time - they shouldn't get duplicated
        foreach ($this->entityOne->getEmailAddresses() as $emailAddress) {
            $this->entityTwo->addEmailAddress($emailAddress);
        }
        $this->assertEquals($this->entityOne->getEmailAddresses()->count(), $this->entityTwo->getEmailAddresses()->count());

        // Remove one of them
        $this->entityTwo->removeEmailAddress($this->entityTwo->getEmailAddresses()->first());
        $this->assertCount(1, $this->entityTwo->getEmailAddresses());

        // now set them in bulk
        $this->entityTwo->setEmailAddresses($this->entityOne->getEmailAddresses());
        $this->assertEquals($this->entityOne->getEmailAddresses()->count(), $this->entityTwo->getEmailAddresses()->count());
        foreach ($this->entityTwo->getEmailAddresses() as $emailAddress) {
            $this->assertContains($emailAddress, $this->entityOne->getEmailAddresses());
        }
    }

    public function testCompetencies()
    {
        $this->assertCount(2, $this->entityOne->getCompetencies());
        $this->assertCount(1, $this->entityTwo->getCompetencies());

        $this->assertEquals($this->entityTwo, $this->entityTwo->getCompetencies()->first()->getTutor());

        // remove the competency from #2
        $this->entityTwo->removeCompetency($this->entityTwo->getCompetencies()->first());
        $this->assertCount(0, $this->entityTwo->getCompetencies());

        // add the competency numbers from #1 to #2
        foreach ($this->entityOne->getCompetencies() as $competency) {
            $this->entityTwo->addCompetency($competency);
        }

        // check that they are the same
        $this->assertEquals($this->entityOne->getCompetencies()->count(), $this->entityTwo->getCompetencies()->count());
        foreach ($this->entityTwo->getCompetencies() as $competency) {
            $this->assertContains($competency, $this->entityOne->getCompetencies());
        }

        // Add them a second time - they shouldn't get duplicated
        foreach ($this->entityOne->getCompetencies() as $competency) {
            $this->entityTwo->addCompetency($competency);
        }
        $this->assertEquals($this->entityOne->getCompetencies()->count(), $this->entityTwo->getCompetencies()->count());

        // Remove one of them
        $this->entityTwo->removeCompetency($this->entityTwo->getCompetencies()->first());
        $this->assertCount(1, $this->entityTwo->getCompetencies());

        // now set them in bulk
        $this->entityTwo->setCompetencies($this->entityOne->getCompetencies());
        $this->assertEquals($this->entityOne->getCompetencies()->count(), $this->entityTwo->getCompetencies()->count());
        foreach ($this->entityTwo->getCompetencies() as $competency) {
            $this->assertContains($competency, $this->entityOne->getCompetencies());
        }
    }

    public function testNotes()
    {
        $this->assertCount(2, $this->entityOne->getNotes());
        $this->assertCount(1, $this->entityTwo->getNotes());

        $this->assertEquals($this->entityTwo, $this->entityTwo->getNotes()->first()->getTutor());

        // remove the Note from #2
        $this->assertTrue($this->entityTwo->hasNote());
        $this->entityTwo->removeNote($this->entityTwo->getNotes()->first());
        $this->assertCount(0, $this->entityTwo->getNotes());
        $this->assertFalse($this->entityTwo->hasNote());

        // add the Notes from #1 to #2
        foreach ($this->entityOne->getNotes() as $note) {
            $this->entityTwo->addNote($note);
        }

        // check that they are the same
        $this->assertEquals($this->entityOne->getNotes()->count(), $this->entityTwo->getNotes()->count());
        foreach ($this->entityTwo->getNotes() as $note) {
            $this->assertContains($note, $this->entityOne->getNotes());
        }

        // Add them a second time - they shouldn't get duplicated
        foreach ($this->entityOne->getNotes() as $note) {
            $this->entityTwo->addNote($note);
        }
        $this->assertEquals($this->entityOne->getNotes()->count(), $this->entityTwo->getNotes()->count());

        // Remove one of them
        $this->entityTwo->removeNote($this->entityTwo->getNotes()->first());
        $this->assertCount(1, $this->entityTwo->getNotes());

        // now set them in bulk
        $this->entityTwo->setNotes($this->entityOne->getNotes());
        $this->assertEquals($this->entityOne->getNotes()->count(), $this->entityTwo->getNotes()->count());
        foreach ($this->entityTwo->getNotes() as $note) {
            $this->assertContains($note, $this->entityOne->getNotes());
        }
    }

    /**
     * @return TutorManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }
}
