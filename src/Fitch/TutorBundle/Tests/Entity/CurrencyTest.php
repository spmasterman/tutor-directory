<?php

namespace Fitch\TutorBundle\Tests\Entity;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\CurrencyManagerInterface;

class CurrencyTest extends FixturesWebTestCase
{
    public function testToString()
    {
        $entityOne = $this->getModelManager()->findById(1);
        $this->assertEquals(
            'ONE - Test Currency One',
            (string) $entityOne
        );
    }

    /**
     * @return CurrencyManagerInterface
     */
    public function getModelManager()
    {
        return $this->container->get('fitch.manager.currency');
    }
}
