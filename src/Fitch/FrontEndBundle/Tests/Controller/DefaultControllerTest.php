<?php

namespace Fitch\FrontEndBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\FrontEndBundle\Controller\DefaultController;
use Fitch\TutorBundle\Tests\Controller\TestSlug;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultControllerTest.
 */
class DefaultControllerTest extends FixturesWebTestCase
{
    /**
     * Removed the Address.
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function performMockedWidgetControl($data)
    {
        $request = $this->getMockedRequest($data);

        // Call the Controller Update
        $controller = new DefaultController();
        $controller->setContainer($this->container);

        return $controller->widgetControlAction($request);
    }

    /**
     * @param array $parameters
     *
     * @return Request
     */
    private function getMockedRequest($parameters)
    {
        // Create a request payload that should update the EAV widget
        $requestBag = new ParameterBag($parameters);

        // Set that up in a Stub/mock Request
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        /* @var Request $request */
        $request->request = $requestBag;

        return $request;
    }

    /**
     *
     */
    public function testWidgetControl()
    {
        $superAdminId = 5;

        // define a closure that tests things...
        $testBlock = function () use ($superAdminId) {
            // This should create a new EAV entry - we'll test "closed" first as "open" is the default value
            // set in the schema
            $this->performMockedWidgetControl([
                'key' => TestSlug::START_1,
                'state' => 'closed',
            ]);

            $this->assertEquals(
                'closed',
                $this->getUserManager()->findAttributeByName(
                    $this->getUserManager()->findById($superAdminId),
                    TestSlug::START_1
                )->getValue()
            );

            // this should update the existing schema entry
            $this->performMockedWidgetControl([
                'key' => TestSlug::START_1,
                'state' => 'open',
            ]);

            $this->assertEquals(
                'open',
                $this->getUserManager()->findAttributeByName(
                    $this->getUserManager()->findById($superAdminId),
                    TestSlug::START_1
                )->getValue()
            );

            // get all the schema entries (there should only be one)

            $map = $this->getUserManager()->findAttributeGroupAsMap(
                $this->getUserManager()->findById($superAdminId),
                'widget.state'
            );

            $this->assertCount(1, $map);
        };

        // execute the closure as a specific user...
        $this->performTestAsUser($superAdminId, $testBlock);
    }
}
