<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Entity\Address;
use Fitch\TutorBundle\Model\CountryManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fitch\TutorBundle\Entity\Tutor;
use Fitch\TutorBundle\Form\TutorType;

/**
 * Tutor controller.
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
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FitchTutorBundle:Tutor')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Tutor entity.
     *
     * @Route("/", name="tutor_create")
     * @Method("POST")
     * @Template("FitchTutorBundle:Tutor:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Tutor();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tutor_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
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
        $form = $this->createForm(new TutorType($this->get('translator')), $entity, array(
            'action' => $this->generateUrl('tutor_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit',
            array(
                'label' => 'Create',
                'attr' => array(
                    'submit_class' => 'btn-success',
                    'submit_glyph' => 'fa-plus-circle'
        )));

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
        $entity = new Tutor();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Tutor entity.
     *
     * @Route("/{id}", name="tutor_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FitchTutorBundle:Tutor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tutor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
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
        if (!$tutor->hasAddress()) {
            $address = new Address();
            $address->setCountry($this->getCountryManager()->getDefaultCountry());
            $tutor->addAddress($address);
        }

        $editForm = $this->createEditForm($tutor);
        $deleteForm = $this->createDeleteForm($tutor->getId());

        return array(
            'tutor'      => $tutor,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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
        $form = $this->createForm(new TutorType($this->get('translator'), $this->getCountryManager()), $entity, array(
            'action' => $this->generateUrl('tutor_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit',
            array(
                'label' => $this->get('translator')->trans('navigation.update'),
                'attr' => array(
                    'submit_class' => 'btn-success',
                    'submit_glyph' => 'fa-check-circle'
            )));

        return $form;
    }
    /**
     * Edits an existing Tutor entity.
     *
     * @Route("/{id}", name="tutor_update")
     * @Method("PUT")
     * @Template("FitchTutorBundle:Tutor:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FitchTutorBundle:Tutor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tutor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tutor_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Tutor entity.
     *
     * @Route("/{id}", name="tutor_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FitchTutorBundle:Tutor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tutor entity.');
            }

            $em->remove($entity);
            $em->flush();
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
            ->setAction($this->generateUrl('tutor_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit',
                array(
                    'label' => $this->get('translator')->trans('navigation.delete'),
                        'attr' => array(
                            'submit_class' => 'btn-danger',
                            'submit_glyph' => 'fa-exclamation-circle'
                )))
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
}
