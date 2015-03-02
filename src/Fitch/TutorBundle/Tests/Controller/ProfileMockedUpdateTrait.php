<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\TutorBundle\Controller\ProfileController;
use Fitch\TutorBundle\Entity\Tutor;
use Symfony\Component\HttpFoundation\ParameterBag;

trait ProfileMockedUpdateTrait
{
    /**
     * @param Tutor  $tutor
     * @param string $name
     * @param array  $requestExtras
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function performMockedUpdate(Tutor $tutor, $name, $requestExtras = [])
    {
        // Create a response payload that should change the Address
        $requestBag = new ParameterBag(array_merge([
            'pk' => $tutor->getId(),
            'name' => $name,
        ], $requestExtras));

        // Set that up in a Stub/mock Request
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $request->request = $requestBag;

        // Call the Controller Update
        $controller = new ProfileController();
        $controller->setContainer($this->container);

        return $controller->updateAction($request);
    }
}
