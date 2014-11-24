<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\CommonBundle\Entity\IdentityTraitInterface;
use Fitch\CommonBundle\Exception\UnknownMethodException;
use Fitch\TutorBundle\Entity\Note;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Model\AddressManager;
use Fitch\TutorBundle\Model\CompetencyLevelManager;
use Fitch\TutorBundle\Model\CompetencyManager;
use Fitch\TutorBundle\Model\CompetencyTypeManager;
use Fitch\TutorBundle\Model\CountryManager;
use Fitch\TutorBundle\Model\CurrencyManager;
use Fitch\TutorBundle\Model\EmailManager;
use Fitch\TutorBundle\Model\NoteManager;
use Fitch\TutorBundle\Model\OperatingRegionManager;
use Fitch\TutorBundle\Model\PhoneManager;
use Fitch\TutorBundle\Model\RateManager;
use Fitch\TutorBundle\Model\StatusManager;
use Fitch\TutorBundle\Model\TutorManager;
use Fitch\TutorBundle\Model\TutorTypeManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/{id}/{tab}", requirements={"id" = "\d+"}, name="tutor_profile", options={"expose"=true})
     * @Method("GET")
     * @Template()
     *
     * @param Tutor $tutor
     *
     * @param $tab
     * @return array
     */
    public function showAction(Tutor $tutor, $tab = 'profile')
    {
        return [
            'tutor' => $tutor,
            'user' => $this->getUser(),
            'tab' => $tab,
            'rateManager' => $this->getRateManager()
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
            $relatedEntity = null;

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
                    $relatedEntity = $address;
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
                    $relatedEntity = $email;
                    break ;
                case 'phone' :
                    $phoneId = $request->request->get('phonePk');
                    if ($phoneId) {
                        $phone = $this->getPhoneManager()->findById($phoneId);
                    } else {
                        $phone = $this->getPhoneManager()->createPhone();
                        $tutor->addPhoneNumber($phone);
                    }
                    $phone
                        ->setType($value['type'])
                        ->setNumber($value['number'])
                        ->setCountry($this->getCountryManager()->findById($value['country']))
                    ;
                    $relatedEntity = $phone;
                    break ;
                case 'rate':
                    $rateId = $request->request->get('ratePk');
                    if ($rateId) {
                        $rate = $this->getRateManager()->findById($rateId);
                    } else {
                        $rate = $this->getRateManager()->createRate();
                        $tutor->addRate($rate);
                    }
                    $rate
                        ->setName($value['name'])
                        ->setAmount($value['amount'])
                    ;
                    $relatedEntity = $rate;
                    break ;
                case 'tutor_type':
                    $tutorType = $this->getTutorTypeManager()->findById($value);
                    $tutor->setTutorType($tutorType);
                    break ;
                case 'status':
                    $status = $this->getStatusManager()->findById($value);
                    $tutor->setStatus($status);
                    break ;
                case 'region':
                    $region = $this->getOperatingRegionManager()->findById($value);
                    $tutor->setRegion($region);
                    break ;
                case 'currency':
                    $currency = $this->getCurrencyManager()->findById($value);
                    $tutor->setCurrency($currency);
                    break ;
                case 'note':
                    $noteId = $request->request->get('notePk');
                    if ($noteId) {
                        $note = $this->getNoteManager()->findById($noteId);
                    } else {
                        $note = $this->getNoteManager()->createNote();
                        $note
                            ->setAuthor($this->getUser())
                            ->setKey($request->request->get('noteKey'))
                        ;
                        $tutor->addNote($note);
                    }
                    $note->setBody($value);
                    $relatedEntity = $note;
                    break;
                default :
                    $setter = 'set' . ucfirst($name);
                    if (is_callable([$tutor, $setter])) {
                        $tutor->$setter($value);
                    } else {
                        throw new UnknownMethodException($setter . ' is not a valid Tutor method');
                    }
            }

            $this->getTutorManager()->saveTutor($tutor);

        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'success' => true,
            'id' => $relatedEntity instanceof IdentityTraitInterface ? $relatedEntity->getId() : null,
            'detail' => $relatedEntity instanceof Note ? $relatedEntity->getProvenance() : null,
        ]);
    }

    /**
     * Returns the countries as a JSON Array
     *
     * @Route("/all/countries", name="all_countries", options={"expose"=true})
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function allCountriesAction(){
        $out = [];
        foreach($this->getCountryManager()->findAllSorted() as $country) {
            $out[] = [
                'value' => $country->getId(),
                'text' => $country->getName(),
                'dialingCode' => $country->getDialingCode()
            ];
        }
        return new JsonResponse($out);
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
     * @return PhoneManager
     */
    private function getPhoneManager()
    {
        return $this->get('fitch.manager.phone');
    }

    /**
     * @return RateManager
     */
    private function getRateManager()
    {
        return $this->get('fitch.manager.rate');
    }

    /**
     * @return CurrencyManager
     */
    private function getCurrencyManager()
    {
        return $this->get('fitch.manager.currency');
    }

    /**
     * @return NoteManager
     */
    private function getNoteManager()
    {
        return $this->get('fitch.manager.note');
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

    /**
     * @return TutorTypeManager
     */
    private function getTutorTypeManager()
    {
        return $this->get('fitch.manager.tutor_type');
    }
}
