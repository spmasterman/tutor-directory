<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\TutorBundle\Model\EmailManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Email controller.
 *
 * @Route("/editor/email")
 */
class EmailController extends Controller
{
    /**
     * Removes an Email.
     *
     * @Route(
     *      "/remove",
     *      name="email_ajax_remove",
     *      options={"expose"=true},
     *      condition="request.request.has('pk') and request.request.get('pk') != '0'"
     * )
     *
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
            $manager = $this->getEmailManager();
            $manager->removeEntity($manager->findById($request->request->get('pk')));
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'success' => true,
        ]);
    }

    /**
     * @return EmailManagerInterface
     */
    private function getEmailManager()
    {
        return $this->get('fitch.manager.email');
    }
}
