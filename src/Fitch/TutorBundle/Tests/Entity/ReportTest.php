<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\ReportManagerInterface;

class ReportTest extends FixturesWebTestCase
{
    /**
     * Not much to test - reportDefinition does all the work
     */
    public function testName()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals('Test Report One', $entityOne->getName());
    }

    /**
     * @return ReportManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.report');
    }
}
