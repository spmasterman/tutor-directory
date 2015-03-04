<?php

namespace Fictch\UserBundle\Tests\Form;

use Fitch\TutorBundle\Entity\Country;
use Fitch\TutorBundle\Form\Type\CountryType;
use Fitch\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\Form\Test\TypeTestCase;

class RegisrationFormTest extends TypeTestCase
{
    /**
     * @dataProvider getValidTestData
     */
    public function testSubmitValidData($data)
    {
        $type = new RegistrationFormType();
        $form = $this->factory->create($type);

        $object = new User();
        $object->fromArray($data);

        // submit the data to the form directly
        $form->submit($data);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($data) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function getValidTestData()
    {
        return [
            [
                'data' => [
                    'name' => 'Test',
                    'twoDigitCode' => 'US',
                    'threeDigitCode' => 'USA',
                    'dialingCode' => '+1',
                    'preferred' => true,
                    'active' => true,
                ],
            ],
            [
                'data' => [
                    'active' => true,
                ],
            ],
        ];
    }
}
