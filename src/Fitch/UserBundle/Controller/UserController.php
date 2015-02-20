<?php

namespace Fitch\UserBundle\Controller;

use Fitch\UserBundle\Model\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fitch\UserBundle\Entity\User;
use Fitch\UserBundle\Form\Type\NewUserType;
use Fitch\UserBundle\Form\Type\EditUserType;

/**
 * User controller.
 *
 * @Route("/user")
 * @Security("has_role('ROLE_CAN_MANAGE_USERS')")
 *     Already protected in security.yml - but adding check here too
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @Route("/", name="user")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $security = $this->get('security.context');
        $allowedToSwitch = false;
        $originalUser = null;

        if ($security->isGranted('ROLE_SUPER_ADMIN') || $security->isGranted('ROLE_PREVIOUS_ADMIN')) {
            $allowedToSwitch = true;
        }

        return [
            'users' => $this->getUserManager()->findAll(),
            'allowedToSwitch' => $allowedToSwitch,
            'originalUser' => $originalUser
        ];
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/", name="user_create")
     * @Method("POST")
     * @Template("FitchUserBundle:User:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $user = $this->getUserManager()->createUser();
        $form = $this->createCreateForm($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getUserManager()->saveUser($user);
            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return [
            'user' => $user,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $user
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $user)
    {
        $form = $this->createForm(new NewUserType(), $user, [
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit',
            array(
                'label' => 'Create',
                'attr' => array(
                    'submit_class' => 'btn-success',
                    'submit_glyph' => 'fa-plus-circle'
        )));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new", name="user_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $user = $this->getUserManager()->createUser();
        $form   = $this->createCreateForm($user);

        return [
            'user' => $user,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     * @Template()
     *
     * @param User $user
     *
     * @return array
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user->getId());

        return [
            'user'      => $user,
            'delete_form' => $deleteForm->createView(),
            'logs' => $this->getUserManager()->getLogs($user)
        ];
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method("GET")
     * @Template()
     *
     * @param User $user
     *
     * @return array
     */
    public function editAction(User $user)
    {
        $editForm = $this->createEditForm($user);
        $deleteForm = $this->createDeleteForm($user->getId());

        return [
            'user'      => $user,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param User $user
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(User $user)
    {
        $form = $this->createForm(new EditUserType(), $user, [
            'action' => $this->generateUrl('user_update', ['id' => $user->getId()]),
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
     * Edits an existing User entity.
     *
     * @Route("/{id}", name="user_update")
     * @Method("PUT")
     * @Template("FitchUserBundle:User:edit.html.twig")
     *
     * @param Request $request
     * @param User $user
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user->getId());
        $editForm = $this->createEditForm($user);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getUserManager()->saveUser($user);

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return [
            'user'      => $user,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getUserManager()->removeUser($user->getId());
        }

        return $this->redirectToRoute('user');
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', ['id' => $id]))
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
     * @return UserManager
     */
    private function getUserManager()
    {
        return $this->get('fitch.manager.user');
    }
}
