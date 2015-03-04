<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Entity\BusinessArea;
use Fitch\TutorBundle\Form\Type\BusinessAreaType;
use Fitch\TutorBundle\Model\BusinessAreaManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * BusinessArea controller.
 *
 * @Route("/admin/business_area")
 */
class BusinessAreaController extends Controller
{
    /**
     * Lists all BusinessArea entities.
     *
     * @Route("/", name="business_area")
     *
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            'businessAreas' => $this->getBusinessAreaManager()->findAll(),
        ];
    }

    /**
     * Creates a new BusinessArea entity.
     *
     * @Route("/", name="business_area_create")
     *
     * @Method("POST")
     * @Template("FitchTutorBundle:BusinessArea:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $businessAreaManager = $this->getBusinessAreaManager();

        $businessArea = $businessAreaManager->createEntity();
        $form = $this->createCreateForm($businessArea);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $businessAreaManager->saveEntity($businessArea);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('business_area.new.success')
            );

            return $this->redirectToRoute('business_area_show', ['id' => $businessArea->getId()]);
        }

        return [
            'businessArea' => $businessArea,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a BusinessArea entity.
     *
     * @param BusinessArea $businessArea The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(BusinessArea $businessArea)
    {
        $form = $this->createForm(new BusinessAreaType(), $businessArea, [
            'action' => $this->generateUrl('business_area_create'),
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
     * Displays a form to create a new BusinessArea entity.
     *
     * @Route("/new", name="business_area_new")
     *
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $businessArea = $this->getBusinessAreaManager()->createEntity();
        $form   = $this->createCreateForm($businessArea);

        return [
            'businessArea' => $businessArea,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a BusinessArea entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="business_area_show")
     *
     * @Method("GET")
     * @Template()
     *
     * @param BusinessArea $businessArea
     *
     * @return array
     */
    public function showAction(BusinessArea $businessArea)
    {
        $deleteForm = $this->createDeleteForm($businessArea->getId());

        return [
            'businessArea' => $businessArea,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing BusinessArea entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="business_area_edit")
     *
     * @Method("GET")
     * @Template()
     *
     * @param BusinessArea $businessArea
     *
     * @return array
     */
    public function editAction(BusinessArea $businessArea)
    {
        $editForm = $this->createEditForm($businessArea);
        $deleteForm = $this->createDeleteForm($businessArea->getId());

        return [
            'businessArea' => $businessArea,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Creates a form to edit a BusinessArea entity.
     *
     * @param BusinessArea $businessArea The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(BusinessArea $businessArea)
    {
        $form = $this->createForm(new BusinessAreaType(), $businessArea, [
            'action' => $this->generateUrl('business_area_update', ['id' => $businessArea->getId()]),
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
     * Edits an existing BusinessArea entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="business_area_update")
     *
     * @Method("PUT")
     * @Template("FitchTutorBundle:BusinessArea:edit.html.twig")
     *
     * @param Request      $request
     * @param BusinessArea $businessArea
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, BusinessArea $businessArea)
    {
        $deleteForm = $this->createDeleteForm($businessArea->getId());
        $editForm = $this->createEditForm($businessArea);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getBusinessAreaManager()->saveEntity($businessArea);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('business_area.edit.success')
            );

            return $this->redirectToRoute('business_area_edit', ['id' => $businessArea->getId()]);
        }

        return [
            'businessArea' => $businessArea,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a BusinessArea entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="business_area_delete")
     *
     * @Method("DELETE")
     *
     * @param Request      $request
     * @param BusinessArea $businessArea
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, BusinessArea $businessArea)
    {
        $form = $this->createDeleteForm($businessArea->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getBusinessAreaManager()->removeEntity($businessArea);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('business_area.delete.success')
            );
        }

        return $this->redirectToRoute('business_area');
    }

    /**
     * Creates a form to delete a BusinessArea entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('business_area_delete', ['id' => $id]))
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
            ->getForm()
        ;
    }

    /**
     * @return BusinessAreaManagerInterface
     */
    private function getBusinessAreaManager()
    {
        return $this->get('fitch.manager.business_area');
    }
}
