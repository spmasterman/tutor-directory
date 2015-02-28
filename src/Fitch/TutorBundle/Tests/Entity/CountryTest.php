<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\CountryManagerInterface;
use Symfony\Component\Validator\Validator;

class CountryTest extends FixturesWebTestCase
{
    /** @var Validator */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->validator = $this->container->get('validator');
    }

    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'Test Country One (ONE) +1',
            (string) $entityOne
        );
    }

    public function testUniqueName()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $entityTwo = $this->getModelManager()->findById(2);

        $entityTwo->setName($entityOne->getName());
        $entityTwo->setDialingCode('+1'); // test data has this as an invalid value - doesnt matter

        $violationList = $this->validator->validate($entityTwo);

        $this->assertEquals(1, $violationList->count());
        $this->assertEquals('This value is already used.', $violationList[0]->getMessage());

        $entityTwo->setName('NEWVALUE');

        $violationList = $this->validator->validate($entityTwo);
        $this->assertEquals(0, $violationList->count());
    }

    public function testNonBlankName()
    {
        $entityTwo = $this->getModelManager()->findById(2);

        $entityTwo->setName('');
        $entityTwo->setDialingCode('+1'); // test data has this as an invalid value - doesnt matter

        $violationList = $this->validator->validate($entityTwo);

        $this->assertEquals(1, $violationList->count());
        $this->assertEquals('This value should not be blank.', $violationList[0]->getMessage());

        $entityTwo->setName('NEWVALUE');

        $violationList = $this->validator->validate($entityTwo);
        $this->assertEquals(0, $violationList->count());
    }

    /**
     * @dataProvider getValidTestDialingCodes
     */
    public function testValidCountryCode($code)
    {
        // dialing code in fixture data is invalid
        $entityTwo = $this->getModelManager()->findById(2);

        $entityTwo->setDialingCode($code);
        $violationList = $this->validator->validate($entityTwo);
        $this->assertEquals(0, $violationList->count());
    }

    /**
     * @dataProvider getInValidTestDialingCodes
     */
    public function testInValidCountryCode($code)
    {
        // dialing code in fixture data is invalid
        $entityTwo = $this->getModelManager()->findById(2);

        $entityTwo->setDialingCode($code);
        $violationList = $this->validator->validate($entityTwo);
        $this->assertEquals(1, $violationList->count());
        $this->assertStringStartsWith('Please enter a correct', $violationList[0]->getMessage());
    }

    public function getValidTestDialingCodes()
    {
        return [
            [
                'code' => '',
            ],
            [
                'code' => '+1',
            ],
            [
                'code' => '+44',
            ],
            [
                'code' => '+599',
            ],
        ];
    }

    public function getInValidTestDialingCodes()
    {
        return [
            [
                'code' => ' ',
            ],
            [
                'code' => ' +1',
            ],
            [
                'code' => '+444',
            ],
            [
                'code' => ' +44',
            ],
            [
                'code' => '+44 ',
            ],
        ];
    }

    public function testUniqueTwoDigitCode()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $entityTwo = $this->getModelManager()->findById(2);

        $entityTwo->setTwoDigitCode($entityOne->getTwoDigitCode());
        $entityTwo->setDialingCode('+1'); // test data has this as an invalid value - doesnt matter

        $violationList = $this->validator->validate($entityTwo);

        $this->assertEquals(1, $violationList->count());
        $this->assertEquals('This value is already used.', $violationList[0]->getMessage());

        $entityTwo->setTwoDigitCode('NEWVALUE');

        $violationList = $this->validator->validate($entityTwo);
        $this->assertEquals(0, $violationList->count());
    }

    /**
     * @return CountryManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.country');
    }
}
