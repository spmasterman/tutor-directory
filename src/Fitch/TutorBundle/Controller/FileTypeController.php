<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Model\FileTypeManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fitch\TutorBundle\Entity\FileType;
use Fitch\TutorBundle\Form\Type\FileTypeType;

/**
 * FileType controller.
 *
 * As a design decision we don't use the entity manager here, but perform all work through the ModelManager class
 *
 * @Route("/admin/type/file")
 */
class FileTypeController extends Controller
{
    /**
     * Lists all FileType entities.
     *
     * @Route("/", name="file_type")
     *
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return [
            'file_types' => $this->getFileTypeManager()->findAll(),
        ];
    }

    /**
     * Creates a new FileType entity.
     *
     * @Route("/", name="file_type_create")
     *
     * @Method("POST")
     * @Template("FitchTutorBundle:FileType:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $fileTypeManager = $this->getFileTypeManager();

        $fileType = $fileTypeManager->createFileType();
        $form = $this->createCreateForm($fileType);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $fileTypeManager->saveFileType($fileType);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('file_type.new.success')
            );

            return $this->redirectToRoute('file_type_show', ['id' => $fileType->getId()]);
        }

        return [
            'fileType' => $fileType,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a FileType entity.
     *
     * @param FileType $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(FileType $entity)
    {
        $form = $this->createForm(new FileTypeType(), $entity, [
            'action' => $this->generateUrl('file_type_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit',
            [
                'label' => 'Create',
                'attr' => [
                    'submit_class' => 'btn-success',
                    'submit_glyph' => 'fa-plus-circle',
        ], ]);

        return $form;
    }

    /**
     * Displays a form to create a new FileType entity.
     *
     * @Route("/new", name="file_type_new")
     *
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $fileType = $this->getFileTypeManager()->createFileType();
        $form   = $this->createCreateForm($fileType);

        return [
            'file_type' => $fileType,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a FileType entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="file_type_show")
     *
     * @Method("GET")
     * @Template()
     *
     * @param FileType $fileType
     *
     * @return array
     */
    public function showAction(FileType $fileType)
    {
        $deleteForm = $this->createDeleteForm($fileType->getId());

        return [
            'file_type' => $fileType,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing FileType entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="file_type_edit")
     *
     * @Method("GET")
     * @Template()
     *
     * @param FileType $fileType
     *
     * @return array
     */
    public function editAction(FileType $fileType)
    {
        $editForm = $this->createEditForm($fileType);
        $deleteForm = $this->createDeleteForm($fileType->getId());

        return [
            'file_type'      => $fileType,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Creates a form to edit a FileType entity.
     *
     * @param FileType $fileType The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(FileType $fileType)
    {
        $form = $this->createForm(new FileTypeType(), $fileType, [
            'action' => $this->generateUrl('file_type_update', ['id' => $fileType->getId()]),
            'method' => 'PUT',
        ]);

        $form->add('submit', 'submit',
            [
                'label' => $this->get('translator')->trans('navigation.update'),
                'attr' => [
                    'submit_class' => 'btn-success',
                    'submit_glyph' => 'fa-check-circle',
            ], ]);

        return $form;
    }

    /**
     * Edits an existing FileType entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="file_type_update")
     *
     * @Method("PUT")
     * @Template("FitchTutorBundle:FileType:edit.html.twig")
     *
     * @param Request  $request
     * @param FileType $fileType
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, FileType $fileType)
    {
        if (!$fileType) {
            throw $this->createNotFoundException('Unable to find FileType entity.');
        }

        $deleteForm = $this->createDeleteForm($fileType->getId());
        $editForm = $this->createEditForm($fileType);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getFileTypeManager()->saveFileType($fileType);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('file_type.edit.success')
            );

            return $this->redirectToRoute('file_type_edit', ['id' => $fileType->getId()]);
        }

        return [
            'file_type'      => $fileType,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a FileType entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="file_type_delete")
     *
     * @Method("DELETE")
     *
     * @param Request  $request
     * @param FileType $fileType
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, FileType $fileType)
    {
        $form = $this->createDeleteForm($fileType->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getFileTypeManager()->removeFileType($fileType->getId());

            $this->addFlash(
                'success',
                $this->get('translator')->trans('file_type.delete.success')
            );
        }

        return $this->redirectToRoute('file_type');
    }

    /**
     * Creates a form to delete a FileType entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('file_type_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit',
                [
                    'label' => $this->get('translator')->trans('navigation.delete'),
                        'attr' => array(
                            'submit_class' => 'btn-danger',
                            'submit_glyph' => 'fa-exclamation-circle',
                ), ])
            ->getForm()
        ;
    }

    /**
     * @return FileTypeManager
     */
    private function getFileTypeManager()
    {
        return $this->get('fitch.manager.file_type');
    }
}
