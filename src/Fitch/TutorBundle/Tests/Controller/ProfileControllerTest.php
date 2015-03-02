<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\CurrencyManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;

/**
 * Class ProfileControllerTest.
 */
class ProfileControllerTest extends FixturesWebTestCase
{
    use ProfileMockedUpdateTrait, AssertBadRequestJsonResponseTrait;

    /**
     * Test editing a real member (f.ex. company)
     */
    public function testUpdateRealMember()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        $tutor->setCompany(TestSlug::START_1);
        $manager->saveEntity($tutor);
        $manager->reloadEntity($tutor);

        $this->assertEquals(TestSlug::START_1, $tutor->getCompany());

        $this->performMockedUpdate($tutor, 'company', [
            'value' => TestSlug::START_2,
        ]);

        $manager->reloadEntity($tutor);

        $this->assertEquals(TestSlug::START_2, $tutor->getCompany());
    }


    /**
     * Test editing a fake member (f.ex. banana)
     */
    public function testUpdateFakeMember()
    {
        $manager = $this->getModelManager();

        // Get the first tutor
        $tutor = $manager->findById(1);

        $response = $this->performMockedUpdate($tutor, 'banana', [
            'value' => TestSlug::START_1,
        ]);

        $this->assertBadRequestJsonResponse($response);
    }

    /**
     * @return TutorManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.tutor');
    }

    /**
     * @return CurrencyManagerInterface
     */
    public function getCurrencyManager()
    {
        return $this->container->get('fitch.manager.currency');
    }
}
