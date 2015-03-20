<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Entity\TutorType;
use Fitch\TutorBundle\Form\Type\TutorTypeType;
use Fitch\TutorBundle\Model\TutorTypeManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * TutorType controller.
 *
 * @Route("/admin/type/tutor")
 */
class TutorTypeController extends Controller
{
    /**
     * Lists all TutorType entities.
     *
     * @Route("/", name="tutor_type")
     *
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            'tutor_types' => $this->getTutorTypeManager()->findAll(),
        ];
    }

    /**
     * Creates a new TutorType entity.
     *
     * @Route("/", name="tutor_type_create")
     *
     * @Method("POST")
     * @Template("FitchTutorBundle:TutorType:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $tutorTypeManager = $this->getTutorTypeManager();

        $tutorType = $tutorTypeManager->createEntity();
        $form = $this->createCreateForm($tutorType);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $tutorTypeManager->saveEntity($tutorType);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('tutor_type.new.success')
            );

            return $this->redirectToRoute('tutor_type_show', ['id' => $tutorType->getId()]);
        }

        return [
            'tutor_type' => $tutorType,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a TutorType entity.
     *
     * @param TutorType $tutorType The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TutorType $tutorType)
    {
        $form = $this->createForm(new TutorTypeType(), $tutorType, [
            'action' => $this->generateUrl('tutor_type_create'),
            'method' => 'POST',
        ]);

        $form->add(
            'submit',
            'submit',
            [
                'label' => 'Create',
                'attr' => [
                    'submit_class' => 'btn-success',
                    'submit_glyph' => 'fa-plus-circle',
                ],
            ]
        );

        return $form;
    }

    /**
     * Displays a form to create a new TutorType entity.
     *
     * @Route("/new", name="tutor_type_new")
     *
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        $tutorType = $this->getTutorTypeManager()->createEntity();
        $form   = $this->createCreateForm($tutorType);

        return [
            'tutor_type' => $tutorType,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a TutorType entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="tutor_type_show")
     *
     * @Method("GET")
     * @Template()
     *
     * @param TutorType $tutorType
     *
     * @return array
     */
    public function showAction(TutorType $tutorType)
    {
        $deleteForm = $this->createDeleteForm($tutorType->getId());

        return [
            'tutor_type'      => $tutorType,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing TutorType entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="tutor_type_edit")
     *
     * @Method("GET")
     * @Template()
     *
     * @param TutorType $tutorType
     *
     * @return array
     */
    public function editAction(TutorType $tutorType)
    {
        $editForm = $this->createEditForm($tutorType);
        $deleteForm = $this->createDeleteForm($tutorType->getId());

        return [
            'tutor_type'      => $tutorType,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Creates a form to edit a TutorType entity.
     *
     * @param TutorType $tutorType The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(TutorType $tutorType)
    {
        $form = $this->createForm(new TutorTypeType(), $tutorType, [
            'action' => $this->generateUrl('tutor_type_update', ['id' => $tutorType->getId()]),
            'method' => 'PUT',
        ]);

        $form->add(
            'submit',
            'submit',
            [
                'label' => $this->get('translator')->trans('navigation.update'),
                'attr' => [
                    'submit_class' => 'btn-success',
                    'submit_glyph' => 'fa-check-circle',
                ],
            ]
        );

        return $form;
    }

    /**
     * Edits an existing TutorType entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="tutor_type_update")
     *
     * @Method("PUT")
     * @Template("FitchTutorBundle:TutorType:edit.html.twig")
     *
     * @param Request   $request
     * @param TutorType $tutorType
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, TutorType $tutorType)
    {
        $deleteForm = $this->createDeleteForm($tutorType->getId());
        $editForm = $this->createEditForm($tutorType);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getTutorTypeManager()->saveEntity($tutorType);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('tutor_type.edit.success')
            );

            return $this->redirectToRoute('tutor_type_edit', ['id' => $tutorType->getId()]);
        }

        return [
            'tutor_type'      => $tutorType,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a TutorType entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="tutor_type_delete")
     *
     * @Method("DELETE")
     *
     * @param Request   $request
     * @param TutorType $tutorType
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, TutorType $tutorType)
    {
        $form = $this->createDeleteForm($tutorType->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getTutorTypeManager()->removeEntity($tutorType);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('tutor_type.delete.success')
            );
        }

        return $this->redirectToRoute('tutor_type');
    }

    /**
     * Creates a form to delete a TutorType entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tutor_type_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add(
                'submit',
                'submit',
                [
                    'label' => $this->get('translator')->trans('navigation.delete'),
                        'attr' => [
                            'submit_class' => 'btn-danger',
                            'submit_glyph' => 'fa-exclamation-circle',
                        ],
                ]
            )
            ->getForm();
    }

    /**
     * @return TutorTypeManagerInterface
     */
    private function getTutorTypeManager()
    {
        return $this->get('fitch.manager.tutor_type');
    }
}
