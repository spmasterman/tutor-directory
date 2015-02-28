<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\EmailManagerInterface;

class EmailTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'test_email_1@example.com',
            (string) $entityOne
        );
    }

    /**
     * @return EmailManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.email');
    }
}
