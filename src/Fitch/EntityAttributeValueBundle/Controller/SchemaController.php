<?php

namespace Fitch\EntityAttributeValueBundle\Controller;

use Fitch\EntityAttributeValueBundle\Form\SchemaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
* @Route("/attribute/schema")
*/
class SchemaController extends Controller
{

    /**
     * @param $id
     * @return array
     */
    public function editAction($id)
    {
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();

        $schema = $em->find('FitchEntityAttributeValueBundle:Schema', $id);
        if ($schema === null) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(new SchemaType(), $schema);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $schema = $form->getData();
                $em->persist($schema);
                $em->flush();
                $this->addFlash('success', $this->get('translator')->trans('Save successful!'));
                return $this->redirectToRoute('attribute_schema_edit', ['id' => $id]);
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Save unsuccessful!'));
            }
        }
        return [
            'form' => $form->createView(),
            'schema' => $schema
        ];
    }
}