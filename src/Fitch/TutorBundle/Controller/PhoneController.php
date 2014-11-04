<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\TutorBundle\Model\PhoneManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Phone controller
 *
 * @Route("/phone")
 */
class PhoneController extends Controller
{
    /**
     * Removes a Phone Number
     *
     * @Route(
     *      "/remove",
     *      name="phone_ajax_remove",
     *      options={"expose"=true},
     *      condition="
                request.request.has('pk') and request.request.get('pk') != '0'
        "
     * )
     * @Method("POST")
     * @Template()
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeAction(Request $request)
    {
        try {
            $this->getPhoneManager()->removePhone($request->request->get('pk'));
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        return new JsonResponse([
            'success' => true,
        ]);
    }

    /**
     * @return PhoneManager
     */
    private function getPhoneManager()
    {
        return $this->get('fitch.manager.phone');
    }
}
