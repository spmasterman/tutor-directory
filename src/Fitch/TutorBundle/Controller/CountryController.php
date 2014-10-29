<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Model\CountryManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fitch\TutorBundle\Entity\Country;
use Fitch\TutorBundle\Form\CountryType;

/**
 * Country controller.
 *
 * As a design decision we don't use the entity manager here, but perform all work through the ModelManager class
 *
 * @Route("/country")
 */
class CountryController extends Controller
{

    /**
     * Lists all Country entities.
     *
     * @Route("/", name="country")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return [
            'countries' => $this->getCountryManager()->findAll()
        ];
    }

    /**
     * Creates a new Country entity.
     *
     * @Route("/", name="country_create")
     * @Method("POST")
     * @Template("FitchTutorBundle:Country:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $countryManager = $this->getCountryManager();

        $country = $countryManager->createCountry();
        $form = $this->createCreateForm($country);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $countryManager->saveCountry($country);

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('country.new.success')
            );


            return $this->redirect($this->generateUrl('country_show', ['id' => $country->getId()]));
        }

        return array(
            'entity' => $country,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Country entity.
    *
    * @param Country $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Country $entity)
    {
        $form = $this->createForm(new CountryType(), $entity, [
            'action' => $this->generateUrl('country_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit',
            [
                'label' => 'Create',
                'attr' => [
                    'submit_class' => 'btn-success',
                    'submit_glyph' => 'fa-plus-circle'
        ]]);

        return $form;
    }

    /**
     * Displays a form to create a new Country entity.
     *
     * @Route("/new", name="country_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $country = $this->getCountryManager()->createCountry();
        $form   = $this->createCreateForm($country);

        return [
            'entity' => $country,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Country entity.
     *
     * @Route("/{id}", name="country_show")
     * @Method("GET")
     * @Template()
     *
     * @param Country $country
     *
     * @return array
     */
    public function showAction(Country $country)
    {
        $deleteForm = $this->createDeleteForm($country->getId());

        return [
            'country' => $country,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Country entity.
     *
     * @Route("/{id}/edit", name="country_edit")
     * @Method("GET")
     * @Template()
     *
     * @param Country $country
     *
     * @return array
     */
    public function editAction(Country $country)
    {
        $editForm = $this->createEditForm($country);
        $deleteForm = $this->createDeleteForm($country->getId());

        return [
            'country'      => $country,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
    * Creates a form to edit a Country entity.
    *
    * @param Country $country The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Country $country)
    {
        $form = $this->createForm(new CountryType(), $country, [
            'action' => $this->generateUrl('country_update', ['id' => $country->getId()]),
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
     * Edits an existing Country entity.
     *
     * @Route("/{id}", name="country_update")
     * @Method("PUT")
     * @Template("FitchTutorBundle:Country:edit.html.twig")
     *
     * @param Request $request
     * @param Country $country
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Country $country)
    {
        if (!$country) {
            throw $this->createNotFoundException('Unable to find Country entity.');
        }

        $deleteForm = $this->createDeleteForm($country->getId());
        $editForm = $this->createEditForm($country);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getCountryManager()->saveCountry($country);

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('country.update.success')
            );

            return $this->redirect($this->generateUrl('country_edit', ['id' => $country->getId()]));
        }

        return [
            'country'      => $country,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Country entity.
     *
     * @Route("/{id}", name="country_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Country $country
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Country $country)
    {
        $form = $this->createDeleteForm($country->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getCountryManager()->removeCountry($country->getId());

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('country.delete.success')
            );
        }

        return $this->redirect($this->generateUrl('country'));
    }

    /**
     * Creates a form to delete a Country entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('country_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit',
                [
                    'label' => $this->get('translator')->trans('navigation.delete'),
                        'attr' => array(
                            'submit_class' => 'btn-danger',
                            'submit_glyph' => 'fa-exclamation-circle'
                )])
            ->getForm()
        ;
    }

    /**
     * @return CountryManager
     */
    private function getCountryManager()
    {
        return $this->get('fitch.manager.country');
    }
}
