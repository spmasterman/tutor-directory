<?php

namespace Fitch\TutorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fitch\TutorBundle\Entity\OperatingRegion;
use Fitch\TutorBundle\Form\OperatingRegionType;

/**
 * OperatingRegion controller.
 *
 * @Route("/region")
 */
class OperatingRegionController extends Controller
{

    /**
     * Lists all OperatingRegion entities.
     *
     * @Route("/", name="region")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FitchTutorBundle:OperatingRegion')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new OperatingRegion entity.
     *
     * @Route("/", name="region_create")
     * @Method("POST")
     * @Template("FitchTutorBundle:OperatingRegion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new OperatingRegion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('region_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a OperatingRegion entity.
    *
    * @param OperatingRegion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(OperatingRegion $entity)
    {
        $form = $this->createForm(new OperatingRegionType(), $entity, array(
            'action' => $this->generateUrl('region_create'),
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
     * Displays a form to create a new OperatingRegion entity.
     *
     * @Route("/new", name="region_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new OperatingRegion();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a OperatingRegion entity.
     *
     * @Route("/{id}", name="region_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FitchTutorBundle:OperatingRegion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OperatingRegion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing OperatingRegion entity.
     *
     * @Route("/{id}/edit", name="region_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FitchTutorBundle:OperatingRegion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OperatingRegion entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a OperatingRegion entity.
    *
    * @param OperatingRegion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(OperatingRegion $entity)
    {
        $form = $this->createForm(new OperatingRegionType(), $entity, array(
            'action' => $this->generateUrl('region_update', array('id' => $entity->getId())),
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
     * Edits an existing OperatingRegion entity.
     *
     * @Route("/{id}", name="region_update")
     * @Method("PUT")
     * @Template("FitchTutorBundle:OperatingRegion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FitchTutorBundle:OperatingRegion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OperatingRegion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('region_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a OperatingRegion entity.
     *
     * @Route("/{id}", name="region_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FitchTutorBundle:OperatingRegion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OperatingRegion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('region'));
    }

    /**
     * Creates a form to delete a OperatingRegion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('region_delete', array('id' => $id)))
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
}
