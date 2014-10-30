<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\TutorBundle\Model\EmailManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Email controller
 *
 * @Route("/email")
 */
class EmailController extends Controller
{
    /**
     * Removes an Email
     *
     * @Route(
     *      "/remove",
     *      name="email_ajax_remove",
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
            $this->getEmailManager()->removeEmail($request->request->get('pk'));
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
     * @return EmailManager
     */
    private function getEmailManager()
    {
        return $this->get('fitch.manager.email');
    }
}