<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Controller\ProfileDataController;

/**
 * Class ProfileDataControllerTest.
 */
class ProfileDataControllerTest extends FixturesWebTestCase
{
    private $savedService;

    /**
     * Test the prototype Action. This action returns a JSON response with a lot of
     * arrays used to populate selects etc, and also a lot of rendered fragments - that depend on
     * the authorised user.
     *
     * We just check that all the right elements are returned in the JSON response
     *
     * It might be wise to check that specific things aren't included in the rendered templates when the user isn't
     * Authorised (isAdmin, isEditor in the controller) - but Im not sure that making the test so tied to the template
     * content is a good idea. Maybe later.
     */
    public function testPrototypeAction()
    {
        $this->injectAuthService(true); // Super Admin User

        // Call the Controller Action
        $controller = new ProfileDataController();
        $controller->setContainer($this->container);

        $result = json_decode($controller->prototypeAction(1)->getContent());

        $this->assertObjectHasAttribute('groupedCountries', $result);
        $this->assertObjectHasAttribute('allCompetencyTypes', $result);
        $this->assertObjectHasAttribute('allCompetencyLevels', $result);
        $this->assertObjectHasAttribute('allLanguages', $result);
        $this->assertObjectHasAttribute('allProficiencies', $result);
        $this->assertObjectHasAttribute('languagePrototype', $result);
        $this->assertObjectHasAttribute('competencyPrototype', $result);
        $this->assertObjectHasAttribute('addressPrototype', $result);
        $this->assertObjectHasAttribute('emailPrototype', $result);
        $this->assertObjectHasAttribute('phonePrototype', $result);
        $this->assertObjectHasAttribute('notePrototype', $result);
        $this->assertObjectHasAttribute('ratePrototype', $result);

        $this->restoreContainer();
    }
    /**
     * This injects a mock into the container in place of the security.authorization_checker service.
     *
     * @param bool $isGranted
     */
    private function injectAuthService($isGranted)
    {
        $mockAuthChecker = $this->getMockBuilder('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mockAuthChecker->expects($this->any())->method('isGranted')->willReturn($isGranted);

        $this->savedService = $this->container->get('security.authorization_checker');
        $this->container->set('security.authorization_checker', $mockAuthChecker);
    }

    private function restoreContainer()
    {
        $this->container->set('security.authorization_checker', $this->savedService);
    }
}
