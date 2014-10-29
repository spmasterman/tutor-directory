<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\AddressManager;
use Fitch\TutorBundle\Model\CountryManager;
use Fitch\TutorBundle\Model\TutorManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Address controller
 *
 * @Route("/address")
 */
class AddressController extends Controller
{


//    /**
//     * Updates a (simple) field on an address record
//     *
//     * @Route(
//     *      "/update",
//     *      name="address_ajax_update",
//     *      options={"expose"=true},
//     *      condition="
//                request.request.has('pk') and request.request.get('pk') >= 0
//            and request.request.has('name') and request.request.get('name') > ''
//            and request.request.has('value')
//        "
//     * )
//     * @Method("POST")
//     * @Template()
//     *
//     * @param Request $request
//     *
//     * @return \Symfony\Component\HttpFoundation\JsonResponse
//     */
//    public function updateAction(Request $request)
//    {
//        try {
//            $newValue = $request->request->get('value');
//
//        } catch (Exception $e) {
//            return new JsonResponse([
//                'success' => false,
//                'message' => $e->getMessage()
//            ]);
//        }
//
//        return new JsonResponse([
//            'success' => true,
//            'newValue' => $newValue,
//        ]);
//    }
}