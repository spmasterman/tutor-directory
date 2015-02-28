<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Model\LanguageManager;
use Fitch\TutorBundle\Model\LanguageManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fitch\TutorBundle\Entity\Language;
use Fitch\TutorBundle\Form\Type\LanguageType;

/**
 * Language controller.
 *
 * As a design decision we don't use the entity manager here, but perform all work through the ModelManager class
 *
 * @Route("/admin/language")
 */
class LanguageController extends Controller
{
    /**
     * Lists all Language entities.
     *
     * @Route("/", name="language")
     *
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return [
            'languages' => $this->getLanguageManager()->findAllSorted(),
        ];
    }

    /**
     * Creates a new Language entity.
     *
     * @Route("/", name="language_create")
     *
     * @Method("POST")
     * @Template("FitchTutorBundle:Language:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $languageManager = $this->getLanguageManager();

        $language = $languageManager->createLanguage();
        $form = $this->createCreateForm($language);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $languageManager->saveEntity($language);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('language.new.success')
            );

            return $this->redirectToRoute('language_show', ['id' => $language->getId()]);
        }

        return [
            'entity' => $language,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Language entity.
     *
     * @param Language $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Language $entity)
    {
        $form = $this->createForm(new LanguageType(), $entity, [
            'action' => $this->generateUrl('language_create'),
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
     * Displays a form to create a new Language entity.
     *
     * @Route("/new", name="language_new")
     *
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $language = $this->getLanguageManager()->createLanguage();
        $form   = $this->createCreateForm($language);

        return [
            'entity' => $language,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Language entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="language_show")
     *
     * @Method("GET")
     * @Template()
     *
     * @param Language $language
     *
     * @return array
     */
    public function showAction(Language $language)
    {
        $deleteForm = $this->createDeleteForm($language->getId());

        return [
            'language' => $language,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Language entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="language_edit")
     *
     * @Method("GET")
     * @Template()
     *
     * @param Language $language
     *
     * @return array
     */
    public function editAction(Language $language)
    {
        $editForm = $this->createEditForm($language);
        $deleteForm = $this->createDeleteForm($language->getId());

        return [
            'language'      => $language,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Creates a form to edit a Language entity.
     *
     * @param Language $language The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Language $language)
    {
        $form = $this->createForm(new LanguageType(), $language, [
            'action' => $this->generateUrl('language_update', ['id' => $language->getId()]),
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
     * Edits an existing Language entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="language_update")
     *
     * @Method("PUT")
     * @Template("FitchTutorBundle:Language:edit.html.twig")
     *
     * @param Request  $request
     * @param Language $language
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Language $language)
    {
        if (!$language) {
            throw $this->createNotFoundException('Unable to find Language entity.');
        }

        $deleteForm = $this->createDeleteForm($language->getId());
        $editForm = $this->createEditForm($language);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getLanguageManager()->saveLanguage($language);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('language.edit.success')
            );

            return $this->redirectToRoute('language_edit', ['id' => $language->getId()]);
        }

        return [
            'language' => $language,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Language entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="language_delete")
     *
     * @Method("DELETE")
     *
     * @param Request  $request
     * @param Language $language
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Language $language)
    {
        $form = $this->createDeleteForm($language->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getLanguageManager()->removeLanguage($language->getId());

            $this->addFlash(
                'success',
                $this->get('translator')->trans('language.delete.success')
            );
        }

        return $this->redirectToRoute('language');
    }

    /**
     * Creates a form to delete a Language entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('language_delete', ['id' => $id]))
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
     * @return LanguageManagerInterface
     */
    private function getLanguageManager()
    {
        return $this->get('fitch.manager.language');
    }
}
