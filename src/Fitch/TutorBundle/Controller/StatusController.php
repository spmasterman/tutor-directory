<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Entity\Status;
use Fitch\TutorBundle\Form\Type\StatusType;
use Fitch\TutorBundle\Model\StatusManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Status controller.
 *
 * As a design decision we don't use the entity manager here, but perform all work through the ModelManager class
 *
 * @Route("/admin/status")
 */
class StatusController extends Controller
{
    /**
     * Lists all Status entities.
     *
     * @Route("/", name="status")
     *
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            'statuses' => $this->getStatusManager()->findAll(),
        ];
    }

    /**
     * Creates a new Status entity.
     *
     * @Route("/", name="status_create")
     *
     * @Method("POST")
     * @Template("FitchTutorBundle:Status:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $statusManager = $this->getStatusManager();

        $status = $statusManager->createEntity();
        $form = $this->createCreateForm($status);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $statusManager->saveEntity($status);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('status.new.success')
            );

            return $this->redirectToRoute('status_show', ['id' => $status->getId()]);
        }

        return [
            'entity' => $status,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Status entity.
     *
     * @param Status $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Status $entity)
    {
        $form = $this->createForm(new StatusType(), $entity, [
            'action' => $this->generateUrl('status_create'),
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
     * Displays a form to create a new Status entity.
     *
     * @Route("/new", name="status_new")
     *
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        $status = $this->getStatusManager()->createEntity();
        $form   = $this->createCreateForm($status);

        return [
            'entity' => $status,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Status entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="status_show")
     *
     * @Method("GET")
     * @Template()
     *
     * @param Status $status
     *
     * @return array
     */
    public function showAction(Status $status)
    {
        $deleteForm = $this->createDeleteForm($status->getId());

        return [
            'status' => $status,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Status entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="status_edit")
     *
     * @Method("GET")
     * @Template()
     *
     * @param Status $status
     *
     * @return array
     */
    public function editAction(Status $status)
    {
        $editForm = $this->createEditForm($status);
        $deleteForm = $this->createDeleteForm($status->getId());

        return [
            'status'      => $status,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Creates a form to edit a Status entity.
     *
     * @param Status $status The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Status $status)
    {
        $form = $this->createForm(new StatusType(), $status, [
            'action' => $this->generateUrl('status_update', ['id' => $status->getId()]),
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
     * Edits an existing Status entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="status_update")
     *
     * @Method("PUT")
     * @Template("FitchTutorBundle:Status:edit.html.twig")
     *
     * @param Request $request
     * @param Status  $status
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Status $status)
    {
        $deleteForm = $this->createDeleteForm($status->getId());
        $editForm = $this->createEditForm($status);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getStatusManager()->saveEntity($status);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('status.edit.success')
            );

            return $this->redirectToRoute('status_edit', ['id' => $status->getId()]);
        }

        return [
            'status'      => $status,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Status entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="status_delete")
     *
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Status  $status
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Status $status)
    {
        $form = $this->createDeleteForm($status->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getStatusManager()->removeEntity($status);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('status.delete.success')
            );
        }

        return $this->redirectToRoute('status');
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
            ->setAction($this->generateUrl('status_delete', ['id' => $id]))
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
     * @return StatusManagerInterface
     */
    private function getStatusManager()
    {
        return $this->get('fitch.manager.status');
    }
}
