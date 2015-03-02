<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\TutorBundle\Controller\ProfileController;
use Fitch\TutorBundle\Entity\Tutor;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

trait AssertBadRequestJsonResponseTrait
{

    /**
     * Assert that the response has Json headers, and is badRequest
     * @param $response
     */
    public function assertBadRequestJsonResponse($response)
    {
        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $this->assertEquals(
            Response::HTTP_BAD_REQUEST,
            $response->getStatusCode()
        );
    }
}
