<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Entity\OperatingRegion;
use Fitch\TutorBundle\Form\Type\OperatingRegionType;
use Fitch\TutorBundle\Model\CurrencyManager;
use Fitch\TutorBundle\Model\OperatingRegionManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * OperatingRegion controller.
 *
 * @Route("/admin/region")
 */
class OperatingRegionController extends Controller
{
    /**
     * Lists all OperatingRegion entities.
     *
     * @Route("/", name="region")
     *
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            'regions' => $this->getOperatingRegionManager()->findAll(),
        ];
    }

    /**
     * Creates a new OperatingRegion entity.
     *
     * @Route("/", name="region_create")
     *
     * @Method("POST")
     * @Template("FitchTutorBundle:OperatingRegion:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $operatingRegionManager = $this->getOperatingRegionManager();

        $region = $operatingRegionManager->createOperatingRegion();
        $form = $this->createCreateForm($region);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $operatingRegionManager->saveOperatingRegion($region);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('operating_region.new.success')
            );

            return $this->redirectToRoute('region_show', ['id' => $region->getId()]);
        }

        return [
            'region' => $region,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a OperatingRegion entity.
     *
     * @param OperatingRegion $region The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(OperatingRegion $region)
    {
        $form = $this->createForm(new OperatingRegionType(), $region, [
            'action' => $this->generateUrl('region_create'),
            'method' => 'POST',
            'currencyManager' => $this->getCurrencyManager(),
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
     * Displays a form to create a new OperatingRegion entity.
     *
     * @Route("/new", name="region_new")
     *
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $region = $this->getOperatingRegionManager()->createOperatingRegion();
        $form   = $this->createCreateForm($region);

        return [
            'region' => $region,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a OperatingRegion entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="region_show")
     *
     * @Method("GET")
     * @Template()
     *
     * @param OperatingRegion $region
     *
     * @return array
     */
    public function showAction(OperatingRegion $region)
    {
        $deleteForm = $this->createDeleteForm($region->getId());

        return [
            'region'      => $region,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing OperatingRegion entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="region_edit")
     *
     * @Method("GET")
     * @Template()
     *
     * @param OperatingRegion $region
     *
     * @return array
     */
    public function editAction(OperatingRegion $region)
    {
        $editForm = $this->createEditForm($region);
        $deleteForm = $this->createDeleteForm($region->getId());

        return [
            'region'      => $region,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Creates a form to edit a OperatingRegion entity.
     *
     * @param OperatingRegion $region The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(OperatingRegion $region)
    {
        $form = $this->createForm(new OperatingRegionType(), $region, [
            'action' => $this->generateUrl('region_update', ['id' => $region->getId()]),
            'method' => 'PUT',
            'currencyManager' => $this->getCurrencyManager(),
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
     * Edits an existing OperatingRegion entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="region_update")
     *
     * @Method("PUT")
     * @Template("FitchTutorBundle:OperatingRegion:edit.html.twig")
     *
     * @param Request         $request
     * @param OperatingRegion $region
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, OperatingRegion $region)
    {
        if (!$region) {
            throw $this->createNotFoundException('Unable to find OperatingRegion entity.');
        }

        $deleteForm = $this->createDeleteForm($region->getId());
        $editForm = $this->createEditForm($region);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getOperatingRegionManager()->saveOperatingRegion($region);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('operating_region.edit.success')
            );

            return $this->redirectToRoute('region_edit', ['id' => $region->getId()]);
        }

        return [
            'region'      => $region,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a OperatingRegion entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="region_delete")
     *
     * @Method("DELETE")
     *
     * @param Request         $request
     * @param OperatingRegion $region
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, OperatingRegion $region)
    {
        $form = $this->createDeleteForm($region->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getOperatingRegionManager()->removeOperatingRegion($region->getId());

            $this->addFlash(
                'success',
                $this->get('translator')->trans('operating_region.delete.success')
            );
        }

        return $this->redirectToRoute('region');
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
            ->setAction($this->generateUrl('region_delete', ['id' => $id]))
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
     * @return OperatingRegionManager
     */
    private function getOperatingRegionManager()
    {
        return $this->get('fitch.manager.operating_region');
    }

    /**
     * @return CurrencyManager
     */
    private function getCurrencyManager()
    {
        return $this->get('fitch.manager.currency');
    }
}
