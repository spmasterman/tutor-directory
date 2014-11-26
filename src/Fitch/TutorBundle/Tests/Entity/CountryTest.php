<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Tests\FixturesWebTestCase;
use Fitch\TutorBundle\Model\CountryManager;

class CountryTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'Test Country One (ONE) +1',
            (string)$entityOne
        );
    }

    /**
     * @return CountryManager
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.country');
    }
}
