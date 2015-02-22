<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Entity\Category;
use Fitch\TutorBundle\Form\Type\CategoryType;
use Fitch\TutorBundle\Model\CategoryManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Category controller.
 *
 * @Route("/admin/type/tutor")
 */
class CategoryController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/", name="category")
     *
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            'categories' => $this->getCategoryManager()->findAll(),
        ];
    }

    /**
     * Creates a new Category entity.
     *
     * @Route("/", name="category_create")
     *
     * @Method("POST")
     * @Template("FitchTutorBundle:Category:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $categoryManager = $this->getCategoryManager();

        $category = $categoryManager->createCategory();
        $form = $this->createCreateForm($category);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $categoryManager->saveCategory($category);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('category.new.success')
            );

            return $this->redirectToRoute('category_show', ['id' => $category->getId()]);
        }

        return [
            'category' => $category,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Category entity.
     *
     * @param Category $category The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Category $category)
    {
        $form = $this->createForm(new CategoryType(), $category, [
            'action' => $this->generateUrl('category_create'),
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
     * Displays a form to create a new Category entity.
     *
     * @Route("/new", name="category_new")
     *
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $category = $this->getCategoryManager()->createCategory();
        $form   = $this->createCreateForm($category);

        return [
            'category' => $category,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Category entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="category_show")
     *
     * @Method("GET")
     * @Template()
     *
     * @param Category $category
     *
     * @return array
     */
    public function showAction(Category $category)
    {
        $deleteForm = $this->createDeleteForm($category->getId());

        return [
            'category' => $category,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="category_edit")
     *
     * @Method("GET")
     * @Template()
     *
     * @param Category $category
     *
     * @return array
     */
    public function editAction(Category $category)
    {
        $editForm = $this->createEditForm($category);
        $deleteForm = $this->createDeleteForm($category->getId());

        return [
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Creates a form to edit a Category entity.
     *
     * @param Category $category The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Category $category)
    {
        $form = $this->createForm(new CategoryType(), $category, [
            'action' => $this->generateUrl('category_update', ['id' => $category->getId()]),
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
     * Edits an existing Category entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="category_update")
     *
     * @Method("PUT")
     * @Template("FitchTutorBundle:Category:edit.html.twig")
     *
     * @param Request  $request
     * @param Category $category
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Category $category)
    {
        if (!$category) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $deleteForm = $this->createDeleteForm($category->getId());
        $editForm = $this->createEditForm($category);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getCategoryManager()->saveCategory($category);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('category.edit.success')
            );

            return $this->redirectToRoute('category_edit', ['id' => $category->getId()]);
        }

        return [
            'category'      => $category,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Category entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="category_delete")
     *
     * @Method("DELETE")
     *
     * @param Request  $request
     * @param Category $category
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createDeleteForm($category->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getCategoryManager()->removeCategory($category->getId());

            $this->addFlash(
                'success',
                $this->get('translator')->trans('category.delete.success')
            );
        }

        return $this->redirectToRoute('category');
    }

    /**
     * Creates a form to delete a Category entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit',
                [
                    'label' => $this->get('translator')->trans('navigation.delete'),
                        'attr' => [
                            'submit_class' => 'btn-danger',
                            'submit_glyph' => 'fa-exclamation-circle',
                ], ])
            ->getForm()
        ;
    }

//    /**
//     * Returns the categories as a JSON Array.
//     *
//     * @Route("/all", name="all_categories")
//     *
//     * @Method("GET")
//     * @Template()
//     *
//     * @return \Symfony\Component\HttpFoundation\JsonResponse
//     */
//    public function allAction()
//    {
//        $out = [];
//        foreach ($this->getCategoryManager()->findAll() as $category) {
//            $out[] = [
//                'value' => $category->getId(),
//                'text' => $category->getName(),
//            ];
//        }
//
//        return new JsonResponse($out);
//    }

    /**
     * @return CategoryManager
     */
    private function getCategoryManager()
    {
        return $this->get('fitch.manager.category');
    }
}
