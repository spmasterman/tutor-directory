<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Form\Type\ReportType;
use Fitch\TutorBundle\Model\CompetencyLevelManager;
use Fitch\TutorBundle\Model\CompetencyTypeManager;
use Fitch\TutorBundle\Model\CurrencyManager;
use Fitch\TutorBundle\Model\RateManager;
use Fitch\TutorBundle\Model\ReportDefinition;
use Fitch\TutorBundle\Model\TutorManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Report controller - manages a filterable, savable report that is a little more dynamic than the searchable table
 * on the front page....
 *
 * @Route("/report")
 */
class ReportController extends Controller
{

    /**
     * Lists all Tutor entities.
     *
     * @Route("", name="report_header")
     * @Method("GET")
     *
     * @Template()
     */
    public function listAction()
    {
        return [
            'form' => $this->createReportForm()->createView()
        ];
    }

    /**
     * View a report entities.
     *
     * @Route("/view", name="report_view")
     * @Method("GET")
     *
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $form = $this->createReportForm();
        $form->handleRequest($request);

        $reportDefinition = new ReportDefinition($form);

        return [
            'form' => $form->createView(),
            'data' => $this->getTutorManager()->getReportData($reportDefinition),
            'definition' => $reportDefinition
        ];
    }

    /**
     * Creates a form to handle the report
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createReportForm()
    {

        //$form = $this->createForm(
        $form =  $this->get('form.factory')->createNamedBuilder('ftbr',
            new ReportType(
                $this->get('translator'),
                $this->getCurrencyManager(),
                $this->getRateManager(),
                $this->getCompetencyTypeManager(),
                $this->getCompetencyLevelManager()
            ),
            null,
            [
                'action' => $this->generateUrl('report_view'),
                'method' => 'GET',
            ]
        )->getForm();

        $form->add('submit', 'submit',
            [
                'label' => 'View Report',
                'attr' => [
                    'submit_class' => 'btn-success',
                    'submit_glyph' => 'fa-arrow-circle-right'
                ]]);

        return $form;
    }

    /**
     * @return RateManager
     */
    private function getRateManager()
    {
        return $this->get('fitch.manager.rate');
    }

    /**
     * @return CurrencyManager
     */
    private function getCurrencyManager()
    {
        return $this->get('fitch.manager.currency');
    }


    /**
     * @return CompetencyTypeManager
     */
    private function getCompetencyTypeManager()
    {
        return $this->get('fitch.manager.competency_type');
    }

    /**
     * @return CompetencyLevelManager
     */
    private function getCompetencyLevelManager()
    {
        return $this->get('fitch.manager.competency_level');
    }

    /**
     * @return TutorManager
     */
    private function getTutorManager()
    {
        return $this->get('fitch.manager.tutor');
    }
}