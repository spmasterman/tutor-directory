<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Model\CompetencyLevelManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fitch\TutorBundle\Entity\CompetencyLevel;
use Fitch\TutorBundle\Form\Type\CompetencyLevelType;

/**
 * CompetencyLevel controller.
 *
 * @Route("/admin/level/competency")
 */
class CompetencyLevelController extends Controller
{
    /**
     * Lists all CompetencyLevel entities.
     *
     * @Route("/", name="competency_level")
     *
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            'competencyLevels' => $this->getCompetencyLevelManager()->findAll(),
        ];
    }

    /**
     * Creates a new CompetencyLevel entity.
     *
     * @Route("/", name="competency_level_create")
     *
     * @Method("POST")
     * @Template("FitchTutorBundle:CompetencyLevel:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $competencyLevelManager = $this->getCompetencyLevelManager();
        $competencyLevel = $competencyLevelManager->createCompetencyLevel();

        $form = $this->createCreateForm($competencyLevel);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $competencyLevelManager->saveEntity($competencyLevel);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('competency_level.new.success')
            );

            return $this->redirectToRoute('competency_level_show', ['id' => $competencyLevel->getId()]);
        }

        return [
            'competencyLevel' => $competencyLevel,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a CompetencyLevel entity.
     *
     * @param CompetencyLevel $competencyLevel The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CompetencyLevel $competencyLevel)
    {
        $form = $this->createForm(new CompetencyLevelType(), $competencyLevel, [
            'action' => $this->generateUrl('competency_level_create'),
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
     * Displays a form to create a new CompetencyLevel entity.
     *
     * @Route("/new", name="competency_level_new")
     *
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $competencyLevel = $this->getCompetencyLevelManager()->createCompetencyLevel();
        $form = $this->createCreateForm($competencyLevel);

        return [
            'competencyLevel' => $competencyLevel,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a CompetencyLevel entity.
     *
     * @Route("/{id}", name="competency_level_show")
     *
     * @Method("GET")
     * @Template()
     *
     * @param CompetencyLevel $competencyLevel
     *
     * @return array
     */
    public function showAction(CompetencyLevel $competencyLevel)
    {
        $deleteForm = $this->createDeleteForm($competencyLevel->getId());

        return [
            'competencyLevel' => $competencyLevel,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing CompetencyLevel entity.
     *
     * @Route("/{id}/edit", name="competency_level_edit")
     *
     * @Method("GET")
     * @Template()
     *
     * @param CompetencyLevel $competencyLevel
     *
     * @return array
     */
    public function editAction(CompetencyLevel $competencyLevel)
    {
        $editForm = $this->createEditForm($competencyLevel);
        $deleteForm = $this->createDeleteForm($competencyLevel->getId());

        return [
            'competencyLevel' => $competencyLevel,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Creates a form to edit a CompetencyLevel entity.
     *
     * @param CompetencyLevel $competencyLevel The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CompetencyLevel $competencyLevel)
    {
        $form = $this->createForm(new CompetencyLevelType(), $competencyLevel, [
            'action' => $this->generateUrl('competency_level_update', ['id' => $competencyLevel->getId()]),
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
     * Edits an existing CompetencyLevel entity.
     *
     * @Route("/{id}", name="competency_level_update")
     *
     * @Method("PUT")
     * @Template("FitchTutorBundle:CompetencyLevel:edit.html.twig")
     *
     * @param Request         $request
     * @param CompetencyLevel $competencyLevel
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, CompetencyLevel $competencyLevel)
    {
        $deleteForm = $this->createDeleteForm($competencyLevel->getId());
        $editForm = $this->createEditForm($competencyLevel);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getCompetencyLevelManager()->saveEntity($competencyLevel);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('competency_level.edit.success')
            );

            return $this->redirectToRoute('competency_level_edit', ['id' => $competencyLevel->getId()]);
        }

        return [
            'competencyLevel' => $competencyLevel,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a CompetencyLevel entity.
     *
     * @Route("/{id}", name="competency_level_delete")
     *
     * @Method("DELETE")
     *
     * @param Request         $request
     * @param CompetencyLevel $competencyLevel
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, CompetencyLevel $competencyLevel)
    {
        $form = $this->createDeleteForm($competencyLevel->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->addFlash(
                'success',
                $this->get('translator')->trans('competency_level.delete.success')
            );

            $this->getCompetencyLevelManager()->removeCompetencyLevel($competencyLevel->getId());
        }

        return $this->redirectToRoute('competency_level');
    }

    /**
     * Creates a form to delete a CompetencyLevel entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('competency_level_delete', ['id' => $id]))
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
     * @return CompetencyLevelManagerInterface
     */
    private function getCompetencyLevelManager()
    {
        return $this->get('fitch.manager.competency_level');
    }
}
