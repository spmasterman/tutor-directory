<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Trait AssertBadRequestJsonResponseTrait
 *
 * Mixin for Controller tests (typically) to allow simple testing of JSON responses
 */
trait AssertBadRequestJsonResponseTrait
{
    /**
     * Assert that the response has Json headers, and is badRequest.
     *
     * @param Response $response
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
