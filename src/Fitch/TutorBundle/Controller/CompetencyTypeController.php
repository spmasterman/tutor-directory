<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Model\CompetencyTypeManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fitch\TutorBundle\Entity\CompetencyType;
use Fitch\TutorBundle\Form\Type\CompetencyTypeType;

/**
 * CompetencyType controller.
 *
 * @Route("/admin/type/competency")
 */
class CompetencyTypeController extends Controller
{

    /**
     * Lists all CompetencyType entities.
     *
     * @Route("/", name="competency_type")
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            'competencyTypes' => $this->getCompetencyTypeManager()->findAll(),
        ];
    }

    /**
     * Creates a new CompetencyType entity.
     *
     * @Route("/", name="competency_type_create")
     * @Method("POST")
     * @Template("FitchTutorBundle:CompetencyType:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $competencyTypeManager = $this->getCompetencyTypeManager();
        $competencyType = $competencyTypeManager->createCompetencyType();

        $form = $this->createCreateForm($competencyType);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $competencyTypeManager->saveCompetencyType($competencyType);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('competency_type.new.success')
            );

            return $this->redirectToRoute('competency_type_show', ['id' => $competencyType->getId()]);
        }

        return [
            'competencyType' => $competencyType,
            'form'   => $form->createView(),
        ];
    }

    /**
    * Creates a form to create a CompetencyType entity.
    *
    * @param CompetencyType $competencyType The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CompetencyType $competencyType)
    {
        $form = $this->createForm(new CompetencyTypeType(), $competencyType, [
            'action' => $this->generateUrl('competency_type_create'),
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
     * Displays a form to create a new CompetencyType entity.
     *
     * @Route("/new", name="competency_type_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $competencyType = $this->getCompetencyTypeManager()->createCompetencyType();
        $form   = $this->createCreateForm($competencyType);

        return [
            'competencyType' => $competencyType,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a CompetencyType entity.
     *
     * @Route("/{id}", name="competency_type_show")
     * @Method("GET")
     * @Template()
     *
     * @param CompetencyType $competencyType
     *
     * @return array
     */
    public function showAction(CompetencyType $competencyType)
    {
        $deleteForm = $this->createDeleteForm($competencyType->getId());

        return [
            'competencyType' => $competencyType,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing CompetencyType entity.
     *
     * @Route("/{id}/edit", name="competency_type_edit")
     * @Method("GET")
     * @Template()
     *
     * @param CompetencyType $competencyType
     *
     * @return array
     */
    public function editAction(CompetencyType $competencyType)
    {
        $editForm = $this->createEditForm($competencyType);
        $deleteForm = $this->createDeleteForm($competencyType->getId());

        return [
            'competencyType' => $competencyType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
    * Creates a form to edit a CompetencyType entity.
    *
    * @param CompetencyType $competencyType The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CompetencyType $competencyType)
    {
        $form = $this->createForm(new CompetencyTypeType(), $competencyType, [
            'action' => $this->generateUrl('competency_type_update', ['id' => $competencyType->getId()]),
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
     * Edits an existing CompetencyType entity.
     *
     * @Route("/{id}", name="competency_type_update")
     * @Method("PUT")
     * @Template("FitchTutorBundle:CompetencyType:edit.html.twig")
     *
     * @param Request $request
     * @param CompetencyType $competencyType
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, CompetencyType $competencyType)
    {
        $deleteForm = $this->createDeleteForm($competencyType->getId());
        $editForm = $this->createEditForm($competencyType);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getCompetencyTypeManager()->saveCompetencyType($competencyType);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('competency_type.edit.success')
            );

            return $this->redirectToRoute('competency_type_edit', ['id' => $competencyType->getId()]);
        }

        return [
            'competencyType' => $competencyType,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a CompetencyType entity.
     *
     * @Route("/{id}", name="competency_type_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param CompetencyType $competencyType
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, CompetencyType $competencyType)
    {
        $form = $this->createDeleteForm($competencyType->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {

            $this->addFlash(
                'success',
                $this->get('translator')->trans('competency_type.delete.success')
            );

            $this->getCompetencyTypeManager()->removeCompetencyType($competencyType->getId());
        }

        return $this->redirectToRoute('competency_type');
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
            ->setAction($this->generateUrl('competency_type_delete', ['id' => $id]))
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
     * @return CompetencyTypeManager
     */
    private function getCompetencyTypeManager()
    {
        return $this->get('fitch.manager.competency_type');
    }
}
