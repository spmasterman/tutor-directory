<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\EmailManager;

class EmailTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'test_email_1@example.com',
            (string)$entityOne
        );
    }

    /**
     * @return EmailManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.email');
    }
}
