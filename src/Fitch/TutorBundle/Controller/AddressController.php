<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\TutorBundle\Model\AddressManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Address controller.
 *
 * @Route("/editor/address")
 */
class AddressController extends Controller
{
    /**
     * Removes an Address.
     *
     * @Route(
     *      "/remove",
     *      name="address_ajax_remove",
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
            $manager = $this->getAddressManager();
            $manager->removeEntity($manager->findById($request->request->get('pk')));
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }

        return new JsonResponse([
            'success' => true,
        ]);
    }

    /**
     * @return AddressManagerInterface
     */
    private function getAddressManager()
    {
        return $this->get('fitch.manager.address');
    }
}
