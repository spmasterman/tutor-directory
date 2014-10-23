<?php

namespace Fitch\TutorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fitch\TutorBundle\Entity\CompetencyType;
use Fitch\TutorBundle\Form\CompetencyTypeType;

/**
 * CompetencyType controller.
 *
 * @Route("/competency/type")
 */
class CompetencyTypeController extends Controller
{

    /**
     * Lists all CompetencyType entities.
     *
     * @Route("/", name="competency_type")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FitchTutorBundle:CompetencyType')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new CompetencyType entity.
     *
     * @Route("/", name="competency_type_create")
     * @Method("POST")
     * @Template("FitchTutorBundle:CompetencyType:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new CompetencyType();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('competency_type_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a CompetencyType entity.
    *
    * @param CompetencyType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CompetencyType $entity)
    {
        $form = $this->createForm(new CompetencyTypeType(), $entity, array(
            'action' => $this->generateUrl('competency_type_create'),
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
     * Displays a form to create a new CompetencyType entity.
     *
     * @Route("/new", name="competency_type_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new CompetencyType();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a CompetencyType entity.
     *
     * @Route("/{id}", name="competency_type_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FitchTutorBundle:CompetencyType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CompetencyType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing CompetencyType entity.
     *
     * @Route("/{id}/edit", name="competency_type_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FitchTutorBundle:CompetencyType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CompetencyType entity.');
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
    * Creates a form to edit a CompetencyType entity.
    *
    * @param CompetencyType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CompetencyType $entity)
    {
        $form = $this->createForm(new CompetencyTypeType(), $entity, array(
            'action' => $this->generateUrl('competency_type_update', array('id' => $entity->getId())),
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
     * Edits an existing CompetencyType entity.
     *
     * @Route("/{id}", name="competency_type_update")
     * @Method("PUT")
     * @Template("FitchTutorBundle:CompetencyType:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FitchTutorBundle:CompetencyType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CompetencyType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('competency_type_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a CompetencyType entity.
     *
     * @Route("/{id}", name="competency_type_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FitchTutorBundle:CompetencyType')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CompetencyType entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('competency_type'));
    }

    /**
     * Creates a form to delete a CompetencyType entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('competency_type_delete', array('id' => $id)))
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
