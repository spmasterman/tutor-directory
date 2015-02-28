<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Entity\Proficiency;
use Fitch\TutorBundle\Form\Type\ProficiencyType;
use Fitch\TutorBundle\Model\ProficiencyManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Proficiency controller.
 *
 * As a design decision we don't use the entity manager here, but perform all work through the ModelManager class
 *
 * @Route("/admin/proficiency")
 */
class ProficiencyController extends Controller
{
    /**
     * Lists all Proficiency entities.
     *
     * @Route("/", name="proficiency")
     *
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return [
            'proficiencies' => $this->getProficiencyManager()->findAll(),
        ];
    }

    /**
     * Creates a new Proficiency entity.
     *
     * @Route("/", name="proficiency_create")
     *
     * @Method("POST")
     * @Template("FitchTutorBundle:Proficiency:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $proficiencyManager = $this->getProficiencyManager();

        $proficiency = $proficiencyManager->createEntity();
        $form = $this->createCreateForm($proficiency);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $proficiencyManager->saveEntity($proficiency);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('proficiency.new.success')
            );

            return $this->redirectToRoute('proficiency_show', ['id' => $proficiency->getId()]);
        }

        return [
            'proficiency' => $proficiency,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Proficiency entity.
     *
     * @param Proficiency $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Proficiency $entity)
    {
        $form = $this->createForm(new ProficiencyType(), $entity, [
            'action' => $this->generateUrl('proficiency_create'),
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
     * Displays a form to create a new Proficiency entity.
     *
     * @Route("/new", name="proficiency_new")
     *
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $proficiency = $this->getProficiencyManager()->createEntity();
        $form   = $this->createCreateForm($proficiency);

        return [
            'proficiency' => $proficiency,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Proficiency entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="proficiency_show")
     *
     * @Method("GET")
     * @Template()
     *
     * @param Proficiency $proficiency
     *
     * @return array
     */
    public function showAction(Proficiency $proficiency)
    {
        $deleteForm = $this->createDeleteForm($proficiency->getId());

        return [
            'proficiency' => $proficiency,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Proficiency entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="proficiency_edit")
     *
     * @Method("GET")
     * @Template()
     *
     * @param Proficiency $proficiency
     *
     * @return array
     */
    public function editAction(Proficiency $proficiency)
    {
        $editForm = $this->createEditForm($proficiency);
        $deleteForm = $this->createDeleteForm($proficiency->getId());

        return [
            'proficiency'      => $proficiency,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Creates a form to edit a Proficiency entity.
     *
     * @param Proficiency $proficiency The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Proficiency $proficiency)
    {
        $form = $this->createForm(new ProficiencyType(), $proficiency, [
            'action' => $this->generateUrl('proficiency_update', ['id' => $proficiency->getId()]),
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
     * Edits an existing Proficiency entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="proficiency_update")
     *
     * @Method("PUT")
     * @Template("FitchTutorBundle:Proficiency:edit.html.twig")
     *
     * @param Request     $request
     * @param Proficiency $proficiency
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Proficiency $proficiency)
    {
        if (!$proficiency) {
            throw $this->createNotFoundException('Unable to find Proficiency entity.');
        }

        $deleteForm = $this->createDeleteForm($proficiency->getId());
        $editForm = $this->createEditForm($proficiency);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getProficiencyManager()->saveEntity($proficiency);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('proficiency.edit.success')
            );

            return $this->redirectToRoute('proficiency_edit', ['id' => $proficiency->getId()]);
        }

        return [
            'proficiency'      => $proficiency,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Proficiency entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="proficiency_delete")
     *
     * @Method("DELETE")
     *
     * @param Request     $request
     * @param Proficiency $proficiency
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Proficiency $proficiency)
    {
        $form = $this->createDeleteForm($proficiency->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getProficiencyManager()->removeEntity($proficiency);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('proficiency.delete.success')
            );
        }

        return $this->redirectToRoute('proficiency');
    }

    /**
     * Creates a form to delete a Proficiency entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('proficiency_delete', ['id' => $id]))
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
     * Returns the countries as a JSON Array.
     *
     * @Route("/all", name="all_proficiencies", options={"expose"=true})
     *
     * @Method("GET")
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function allAction()
    {
        $out = [];
        foreach ($this->getProficiencyManager()->findAll() as $proficiency) {
            $out[] = [
                'value' => $proficiency->getId(),
                'text' => (string) $proficiency,
            ];
        }

        return new JsonResponse($out);
    }

    /**
     * @return ProficiencyManagerInterface
     */
    private function getProficiencyManager()
    {
        return $this->get('fitch.manager.proficiency');
    }
}
