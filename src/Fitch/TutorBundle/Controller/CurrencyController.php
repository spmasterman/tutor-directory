<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Model\CurrencyManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fitch\TutorBundle\Entity\Currency;
use Fitch\TutorBundle\Form\CurrencyType;

/**
 * Currency controller.
 *
 * As a design decision we don't use the entity manager here, but perform all work through the ModelManager class
 *
 * @Route("/currency")
 */
class CurrencyController extends Controller
{

    /**
     * Lists all Currency entities.
     *
     * @Route("/", name="currency")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return [
            'currencies' => $this->getCurrencyManager()->findAll()
        ];
    }

    /**
     * Creates a new Currency entity.
     *
     * @Route("/", name="currency_create")
     * @Method("POST")
     * @Template("FitchTutorBundle:Currency:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $currencyManager = $this->getCurrencyManager();

        $currency = $currencyManager->createCurrency();
        $form = $this->createCreateForm($currency);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $currencyManager->saveCurrency($currency);

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('currency.new.success')
            );


            return $this->redirect($this->generateUrl('currency_show', ['id' => $currency->getId()]));
        }

        return array(
            'entity' => $currency,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Currency entity.
    *
    * @param Currency $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Currency $entity)
    {
        $form = $this->createForm(new CurrencyType(), $entity, [
            'action' => $this->generateUrl('currency_create'),
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
     * Displays a form to create a new Currency entity.
     *
     * @Route("/new", name="currency_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $currency = $this->getCurrencyManager()->createCurrency();
        $form   = $this->createCreateForm($currency);

        return [
            'entity' => $currency,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Currency entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="currency_show")
     * @Method("GET")
     * @Template()
     *
     * @param Currency $currency
     *
     * @return array
     */
    public function showAction(Currency $currency)
    {
        $deleteForm = $this->createDeleteForm($currency->getId());

        return [
            'currency' => $currency,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Currency entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="currency_edit")
     * @Method("GET")
     * @Template()
     *
     * @param Currency $currency
     *
     * @return array
     */
    public function editAction(Currency $currency)
    {
        $editForm = $this->createEditForm($currency);
        $deleteForm = $this->createDeleteForm($currency->getId());

        return [
            'currency'      => $currency,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
    * Creates a form to edit a Currency entity.
    *
    * @param Currency $currency The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Currency $currency)
    {
        $form = $this->createForm(new CurrencyType(), $currency, [
            'action' => $this->generateUrl('currency_update', ['id' => $currency->getId()]),
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
     * Edits an existing Currency entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="currency_update")
     * @Method("PUT")
     * @Template("FitchTutorBundle:Currency:edit.html.twig")
     *
     * @param Request $request
     * @param Currency $currency
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Currency $currency)
    {
        if (!$currency) {
            throw $this->createNotFoundException('Unable to find Currency entity.');
        }

        $deleteForm = $this->createDeleteForm($currency->getId());
        $editForm = $this->createEditForm($currency);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getCurrencyManager()->saveCurrency($currency);

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('currency.edit.success')
            );

            return $this->redirect($this->generateUrl('currency_edit', ['id' => $currency->getId()]));
        }

        return [
            'currency' => $currency,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Currency entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="currency_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Currency $currency
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Currency $currency)
    {
        $form = $this->createDeleteForm($currency->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getCurrencyManager()->removeCurrency($currency->getId());

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('currency.delete.success')
            );
        }

        return $this->redirect($this->generateUrl('currency'));
    }

    /**
     * Creates a form to delete a Currency entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('currency_delete', ['id' => $id]))
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
     * Returns the currencies as a JSON Array
     *
     * @Route("/all", name="all_currencies", options={"expose"=true})
     * @Method("GET")
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */

    public function allAction(){
        $out = [];
        foreach($this->getCurrencyManager()->findAllSorted() as $currency) {
            $out[] = [
                'value' => $currency->getId(),
                'text' => $currency->getName(),
            ];
        }
        return new JsonResponse($out);
    }

    /**
     * @return CurrencyManager
     */
    private function getCurrencyManager()
    {
        return $this->get('fitch.manager.currency');
    }
}
