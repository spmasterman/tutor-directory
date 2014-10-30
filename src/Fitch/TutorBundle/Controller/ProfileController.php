<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\AddressManager;
use Fitch\TutorBundle\Model\CountryManager;
use Fitch\TutorBundle\Model\EmailManager;
use Fitch\TutorBundle\Model\OperatingRegionManager;
use Fitch\TutorBundle\Model\StatusManager;
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
            $name = preg_replace('/\d/', '', $name); // collections are numbered address1, address2 etc

            $value = $request->request->get('value');
            $newRelatedEntity = null;

            switch ($name) {
                case 'address':
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
                        ->setCountry($this->getCountryManager()->findById($value['country']))
                    ;
                    $newRelatedEntity = $address;
                    break ;
                case 'email' :
                    $emailId = $request->request->get('emailPk');
                    if ($emailId) {
                        $email = $this->getEmailManager()->findById($emailId);
                    } else {
                        $email = $this->getEmailManager()->createEmail();
                        $tutor->addEmailAddress($email);
                    }
                    $email
                        ->setType($value['type'])
                        ->setAddress($value['address'])
                    ;
                    $newRelatedEntity = $email;
                    break ;
                case 'status':
                    $status = $this->getStatusManager()->findById($value);
                    $tutor->setStatus($status);
                    break ;
                case 'region':
                    $region = $this->getOperatingRegionManager()->findById($value);
                    $tutor->setRegion($region);
                    break ;
                default :
                    $setter = 'set' . ucfirst($name);
                    if (is_callable([$tutor, $setter])) {
                        $tutor->$setter($value);
                    }
            }

            $this->getTutorManager()->saveTutor($tutor);

        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'id' => $newRelatedEntity instanceof IdentityTraitInterface ? $newRelatedEntity->getId() : null,
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

    /**
     * @return EmailManager
     */
    private function getEmailManager()
    {
        return $this->get('fitch.manager.email');
    }

    /**
     * @return StatusManager
     */
    private function getStatusManager()
    {
        return $this->get('fitch.manager.status');
    }

    /**
     * @return OperatingRegionManager
     */
    private function getOperatingRegionManager()
    {
        return $this->get('fitch.manager.operating_region');
    }
}
