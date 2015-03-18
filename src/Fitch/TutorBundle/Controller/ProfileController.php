<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\CommonBundle\Entity\IdentityEntityInterface;
use Fitch\CommonBundle\Exception\ClassNotFoundException;
use Fitch\CommonBundle\Exception\UnknownMethodException;
use Fitch\TutorBundle\Entity\Note;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Controller\Profile\ProfileUpdateFactory;
use Fitch\TutorBundle\Model\RateManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Tutor Profile controller.
 *
 * @Route("/profile")
 */
class ProfileController extends Controller
{
    /**
     * Finds and displays a Tutor entity.
     *
     * @Route("/{id}/{tab}", requirements={"id" = "\d+"}, name="tutor_profile", options={"expose"=true})
     *
     * @Method("GET")
     * @Template()
     *
     * @param Tutor  $tutor
     * @param string $tab
     *
     * @return array
     */
    public function showAction(Tutor $tutor, $tab = 'profile')
    {
        return [
            'tutor' => $tutor,
            'user' => $this->getUser(),
            'tab' => $tab,
            'rateManager' => $this->getRateManager(),
            'isEditor' => $this->isGranted('ROLE_CAN_EDIT_TUTOR'),
            'isAdmin' => $this->isGranted('ROLE_CAN_ACCESS_SENSITIVE_DATA'),
            'delete_form' => $this->createDeleteForm($tutor->getId())->createView(),
        ];
    }

    /**
     * Updates a (simple) field on a tutor record.
     *
     * @Route(
     *      "/update",
     *      name="tutor_ajax_update",
     *      options={"expose"=true},
     *      condition="request.request.has('pk') and request.request.get('pk') > 0",
     *      condition="request.request.has('name') and request.request.get('name') > '' ",
     *      condition="request.request.has('value')"
     * )
     *
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

            try {
                $profileUpdateHandler = ProfileUpdateFactory::getUpdater($name, $this->container);
                $relatedEntity = $profileUpdateHandler->update($request, $tutor, $value);
            } catch (ClassNotFoundException $e) {
                // try a simple field...
                $setter = 'set'.ucfirst($name);
                if (is_callable([$tutor, $setter])) {
                    $tutor->$setter($value);
                } else {
                    throw new UnknownMethodException($setter.' is not a valid Tutor method');
                }
                $relatedEntity = null;
            }

            $this->getTutorManager()->saveEntity($tutor);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'success' => true,
            'id' => $relatedEntity instanceof IdentityEntityInterface ? $relatedEntity->getId() : null,
            'detail' => $relatedEntity instanceof Note ? $relatedEntity->getProvenance() : null,
        ]);
    }

    /**
     * Deletes a Tutor entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="tutor_delete")
     *
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Tutor   $tutor
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws AccessDeniedHttpException
     */
    public function deleteAction(Request $request, Tutor $tutor)
    {
        if (!$this->isGranted('ROLE_CAN_EDIT_TUTOR')) {
            throw new AccessDeniedHttpException('Unauthorised access!');
        }

        $form = $this->createDeleteForm($tutor->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getTutorManager()->removeEntity($tutor);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('tutor.delete.success')
            );
        }

        return $this->redirectToRoute('home');
    }

    /**
     * Creates a form to delete a Status entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('tutor_delete', ['id' => $id]))
        ->setMethod('DELETE')
        ->add(
            'submit',
            'submit',
            [
                'label' => $this->get('translator')->trans('tutor.delete.action'),
                'attr' => [
                    'submit_class' => 'btn-danger btn-large delete-tutor',
                    'submit_glyph' => 'fa-exclamation-circle',
                ],
            ]
        )
        ->getForm();
    }

    /**
     * @return TutorManagerInterface
     */
    private function getTutorManager()
    {
        return $this->get('fitch.manager.tutor');
    }

    /**
     * @return RateManagerInterface
     */
    private function getRateManager()
    {
        return $this->get('fitch.manager.rate');
    }
}
