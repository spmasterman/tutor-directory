<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\EmailManager;

class FileTest extends FixturesWebTestCase
{
    // Not sure how to test file uploading...
    // Mock the listener possibly?
    // then do a functional test of the controller
    // like here
    // http://stackoverflow.com/questions/12623797/phpunit-test-always-succeeds-both-with-this-once-and-this-never

    public function testNothing()
    {
        $this->assertTrue(true);
    }

    /**
     * @return EmailManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.email');
    }
}
