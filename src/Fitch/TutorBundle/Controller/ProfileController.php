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
 * Tutor Profile controller
 *
 * @Route("/profile")
 */
class ProfileController extends Controller
{

    /**
     * Finds and displays a Tutor entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="tutor_profile")
     * @Method("GET")
     * @Template()
     *
     * @param Tutor $tutor
     *
     * @return array
     */
    public function showAction(Tutor $tutor)
    {
        return [
            'tutor' => $tutor,
        ];
    }

    /**
     * Updates a (simple) field on a tutor record
     *
     * @Route(
     *      "/update",
     *      name="tutor_ajax_update",
     *      options={"expose"=true},
     *      condition="
                request.request.has('pk') and request.request.get('pk') > 0
            and request.request.has('name') and request.request.get('name') > ''
            and request.request.has('value')
        "
     * )
     * @Method("POST")
     * @Template()
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAction(Request $request)
    {
        try {
            $tutor = $this->getTutorManager()->findById($request->request->get('pk'));

            $name = $request->request->get('name');
            $name = preg_replace('/\d/', '', $name);

            $value = $request->request->get('value');

            switch ($name) {
                case 'Address' :
                    $addressId = $request->request->get('addressPk');
                    if ($addressId) {
                        $address = $this->getAddressManager()->findById($addressId);
                    } else {
                        $address = $this->getAddressManager()->createAddress();
                        $tutor->addAddress($address);
                    }
                    $address
                        ->setType($value['type'])
                        ->setStreetPrimary($value['streetPrimary'])
                        ->setStreetSecondary($value['streetSecondary'])
                        ->setCity($value['city'])
                        ->setState($value['state'])
                        ->setZip($value['zip'])
                        ->setCountry($value['country'])
                    ;
                    break ;
                default:
                    $x=1;

            }

            $setter = 'set' . ucfirst();



            $newValue = $request->request->get('value');

        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'newValue' => $newValue,
        ]);

    }

    /**
     * Returns the allowable statuses for a tutor record (this is expected to be related to security of the user and
     * so is not hard coded into the template
     *
     * @Route("/statuses", name="tutor_profile_status")
     * @Method("GET")
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */

    public function statusAction(){
        return new JsonResponse([
            [
                'value' => 'unspecified',
                'text' => 'Not Specified'
            ],
            [
                'value' => 'active',
                'text' => 'Active'
            ],
            [
                'value' => 'unavailable',
                'text' => 'Temporarily Unavailable'
            ],
            [
                'value' => 'inactive',
                'text' => 'Not Active'
            ],
        ]);
    }

    /**
     * @return CountryManager
     */
    private function getCountryManager()
    {
        return $this->get('fitch.manager.country');
    }

    /**
     * @return TutorManager
     */
    private function getTutorManager()
    {
        return $this->get('fitch.manager.tutor');
    }

    /**
     * @return AddressManager
     */
    private function getAddressManager()
    {
        return $this->get('fitch.manager.address');
    }
}
