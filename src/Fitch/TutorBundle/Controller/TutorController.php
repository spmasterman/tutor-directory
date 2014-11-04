<?php

namespace Fitch\TutorBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Fitch\TutorBundle\Entity\Address;
use Fitch\TutorBundle\Model\AddressManager;
use Fitch\TutorBundle\Model\CountryManager;
use Fitch\TutorBundle\Model\OperatingRegionManager;
use Fitch\TutorBundle\Model\StatusManager;
use Fitch\TutorBundle\Model\TutorManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Form\Type\TutorType;

/**
 * Tutor CRUD controller - most tutor interaction is expected to be via specific tailored pages - handled by the
 * ProfileController
 *
 * The templates associated with this controller aren't finished - and wont be unless there is some need to have
 * basic CRUD pages. Given the heavy use of related entities a basic form with embedded collections is not a great UI.
 *
 * @Route("/tutor")
 */
class TutorController extends Controller
{
    /**
     * Lists all Tutor entities.
     *
     * @Route("/", name="tutor")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return [
            'tutors' => $this->getTutorManager()->findAll(),
        ];
    }

    /**
     * Creates a new Tutor entity.
     *
     * @Route("/", name="tutor_create")
     * @Method("POST")
     * @Template("FitchTutorBundle:Tutor:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $tutorManager = $this->getTutorManager();

        $tutor = $tutorManager->createTutor(
            $this->getAddressManager(),
            $this->getCountryManager(),
            $this->getStatusManager(),
            $this->getOperatingRegionManager()
        );

        $form = $this->createCreateForm($tutor);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $tutorManager->saveTutor($tutor);

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('tutor.new.success')
            );

            return $this->redirect($this->generateUrl('tutor_show', ['id' => $tutor->getId()]));
        }

        return [
            'tutor' => $tutor,
            'form'   => $form->createView(),
        ];
    }

    /**
    * Creates a form to create a Tutor entity.
    *
    * @param Tutor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Tutor $entity)
    {
        $form = $this->createForm(new TutorType($this->get('translator'), $this->getCountryManager()), $entity, [
            'action' => $this->generateUrl('tutor_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit',
            [
                'label' => 'Create',
                'attr' => [
                    'submit_class' => 'btn-success',
                    'submit_glyph' => 'fa-plus-circle'
        ]]);

        return $form;
    }

    /**
     * Displays a form to create a new Tutor entity.
     *
     * @Route("/new", name="tutor_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $tutor = $this->getTutorManager()->createTutor(
            $this->getAddressManager(),
            $this->getCountryManager(),
            $this->getStatusManager(),
            $this->getOperatingRegionManager()
        );
        $form   = $this->createCreateForm($tutor);
        return [
            'tutor' => $tutor ,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Tutor entity.
     *
     * @Route("/{id}", name="tutor_show")
     * @Method("GET")
     * @Template()
     *
     * @param Tutor $tutor
     *
     * @return array
     */
    public function showAction(Tutor $tutor)
    {
        $deleteForm = $this->createDeleteForm($tutor->getId());

        return [
            'tutor' => $tutor,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Tutor entity.
     *
     * @Route("/{id}/edit", name="tutor_edit")
     * @Method("GET")
     * @Template()
     *
     * @param Tutor $tutor
     *
     * @return array
     */
    public function editAction(Tutor $tutor)
    {
        $this->getTutorManager()->createDefaultAddressIfRequired(
            $tutor,
            $this->getAddressManager(),
            $this->getCountryManager()
        );

        $editForm = $this->createEditForm($tutor);
        $deleteForm = $this->createDeleteForm($tutor->getId());

        return [
            'tutor'      => $tutor,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
    * Creates a form to edit a Tutor entity.
    *
    * @param Tutor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tutor $entity)
    {
        $form = $this->createForm(new TutorType($this->get('translator'), $this->getCountryManager()), $entity, [
            'action' => $this->generateUrl('tutor_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $form->add('submit', 'submit',
            [
                'label' => $this->get('translator')->trans('navigation.update'),
                'attr' => [
                    'submit_class' => 'btn-success',
                    'submit_glyph' => 'fa-check-circle'
            ]]);

        return $form;
    }

    /**
     * Edits an existing Tutor entity.
     *
     * @Route("/{id}", name="tutor_update")
     * @Method("PUT")
     * @Template("FitchTutorBundle:Tutor:edit.html.twig")
     *
     * @param Request $request
     * @param Tutor $tutor
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Tutor $tutor)
    {
        $addressManager = $this->getAddressManager();
        $originalAddresses = new ArrayCollection();
        foreach ($tutor->getAddresses() as $address) {
            $originalAddresses->add($address);
        }

        $deleteForm = $this->createDeleteForm($tutor->getId());
        $editForm = $this->createEditForm($tutor);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            foreach ($originalAddresses as $address) {
                /** @var Address $address */
                if (false === $tutor->getAddresses()->contains($address)) {
                    // remove the Address from the tutor
                    $tutor->getAddresses()->removeElement($address);
                    $address->setTutor(null);
                    $addressManager->removeAddress($address->getId());
                }
            }
            // This corrects the bidirectional relationship, by calling addAddress on each (remaining) address
            $tutor->setAddresses($tutor->getAddresses());

            $this->getTutorManager()->saveTutor($tutor);

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('tutor.edit.success')
            );

            return $this->redirect($this->generateUrl('tutor_edit', ['id' => $tutor->getId()]));
        }

        return [
            'tutor' => $tutor,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Tutor entity.
     *
     * @Route("/{id}", name="tutor_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Tutor $tutor
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Tutor $tutor)
    {
        $form = $this->createDeleteForm($tutor->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getTutorManager()->removeTutor($tutor->getId());

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('tutor.delete.success')
            );
        }

        return $this->redirect($this->generateUrl('tutor'));
    }

    /**
     * Creates a form to delete a Tutor entity by id.
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
            ->add('submit', 'submit',
                [
                    'label' => $this->get('translator')->trans('navigation.delete'),
                        'attr' => [
                            'submit_class' => 'btn-danger',
                            'submit_glyph' => 'fa-exclamation-circle'
                ]])
            ->getForm()
        ;
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
     * @return OperatingRegionManager
     */
    private function getOperatingRegionManager()
    {
        return $this->get('fitch.manager.operating_region');
    }

    /**
     * @return StatusManager
     */
    private function getStatusManager()
    {
        return $this->get('fitch.manager.status');
    }
}
