<?php

namespace Fictch\TutorBundle\Tests\Form;

use Fitch\TutorBundle\Entity\BusinessArea;
use Fitch\TutorBundle\Form\Type\BusinessAreaType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class BusinessAreaTypeTest.
 */
class BusinessAreaTypeTest extends TypeTestCase
{
    /**
     * @dataProvider getValidTestData
     *
     * @param array $data
     */
    public function testSubmitValidData($data)
    {
        $type = new BusinessAreaType();
        $form = $this->factory->create($type);

        $object = new BusinessArea();
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

    /**
     * @return array
     */
    public function getValidTestData()
    {
        return [
            [
                'data' => [
                    'name' => 'Test',
                    'code' => '123',
                    'prependToCategoryName' => true,
                    'displayAsCode' => true,
                    'default' => true,
                ],
            ],
            [
                'data' => [
                    'name' => '',
                    'code' => '123',
                    'prependToCategoryName' => true,
                    'displayAsCode' => true,
                    'default' => true,
                ],
            ],
        ];
    }
}
