<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Entity\Report;
use Fitch\TutorBundle\Form\Type\ReportDefinitionType;
use Fitch\TutorBundle\Form\Type\ReportType;
use Fitch\TutorBundle\Model\CompetencyLevelManager;
use Fitch\TutorBundle\Model\CompetencyTypeManager;
use Fitch\TutorBundle\Model\CurrencyManager;
use Fitch\TutorBundle\Model\LanguageManager;
use Fitch\TutorBundle\Model\RateManager;
use Fitch\TutorBundle\Model\ReportDefinition;
use Fitch\TutorBundle\Model\ReportManager;
use Fitch\TutorBundle\Model\TutorManager;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Report controller - manages a filterable, savable report that is a little more dynamic than the searchable table
 * on the front page....
 *
 * @Route("/report")
 */
class ReportController extends Controller
{

    /**
     * Lists all Report entities.
     *
     * @Route("", name="report_header")
     * @Method("GET")
     *
     * @Template()
     */
    public function listAction()
    {
        return [
            'reports' => $this->getReportManager()->findAll(),
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

        $reportDefinition = new ReportDefinition($form, $this->isGranted('ROLE_ADMIN'));

        $report = $this->getReportManager()->createReport();
        $report->setDefinition($this->getSerializer()->serialize($reportDefinition, 'json'));

        return [
            'form' => $form->createView(),
            'saveForm' => $this->createCreateForm($report)->createView(),
            'data' => $this->getReportData($reportDefinition),
            'definition' => $reportDefinition,
            'unrestricted' => $this->isGranted('ROLE_ADMIN')
        ];
    }

    /**
     * Show a pre saved report
     *
     * @Route("/show/{id}", name="report_show")
     * @Method("GET")
     *
     * @Template()
     *
     * @param Report $report
     *
     * @return array
     */
    public function showAction(Report $report)
    {
        $reportDefinition = $this->getReportDefinition($report);
        return [
            'data' => $this->getReportData($reportDefinition),
            'definition' => $reportDefinition,
            'unrestricted' => $this->isGranted('ROLE_ADMIN'),
            'report' => $report
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
        $form =  $this->get('form.factory')->createNamedBuilder(
            'ftbr',
            new ReportDefinitionType(
                $this->get('translator'),
                $this->getCurrencyManager(),
                $this->getRateManager(),
                $this->getCompetencyTypeManager(),
                $this->getCompetencyLevelManager(),
                $this->getLanguageManager()
            ),
            null,
            [
                'action' => $this->generateUrl('report_view'),
                'method' => 'GET',
                'csrf_protection' => false
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
     * Creates a form to create a Status entity.
     *
     * @param Report $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Report $entity)
    {
        $form = $this->createForm(new ReportType(), $entity, [
            'action' => $this->generateUrl('report_create'),
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
     * Creates a form to update a Report entity.
     *
     * @param Report $report The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Report $report)
    {
        $form = $this->createForm(new ReportType(), $report, [
            'action' => $this->generateUrl('report_update', ['id' => $report->getId()]),
            'method' => 'PUT',
        ]);

        $form->add('submit', 'submit',
            [
                'label' => 'Update',
                'attr' => [
                    'submit_class' => 'btn-success',
                    'submit_glyph' => 'fa-plus-circle'
                ]]);

        return $form;
    }

    /**
     * Creates a new Status entity.
     *
     * @Route("/", name="report_create")
     * @Method("POST")
     * @Template("FitchTutorBundle:Status:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $reportManager = $this->getReportManager();

        $report = $reportManager->createReport();

        $form = $this->createCreateForm($report);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $report->setCreator($this->getUser());
            $reportManager->saveReport($report);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('report.new.success')
            );

            return $this->redirectToRoute('report_show', ['id' => $report->getId()]);
        }

        return [
            'report' => $report,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Report entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="report_edit")
     * @Method("GET")
     * @Template()
     *
     * @param Report $report
     *
     * @return array
     */
    public function editAction(Report $report)
    {
        $editForm = $this->createEditForm($report);
        $deleteForm = $this->createDeleteForm($report->getId());

        return [
            'report' => $report,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Edits an existing Report entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="report_update")
     * @Method("PUT")
     * @Template("FitchTutorBundle:Report:edit.html.twig")
     *
     * @param Request $request
     * @param Report $report
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Report $report)
    {
        if (!$report) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $deleteForm = $this->createDeleteForm($report->getId());
        $editForm = $this->createEditForm($report);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getReportManager()->saveReport($report);

            $this->addFlash(
                'success',
                $this->get('translator')->trans('report.edit.success')
            );

            return $this->redirectToRoute('report_edit', ['id' => $report->getId()]);
        }

        return [
            'report' => $report,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Report entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="report_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Report $report
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Report $report)
    {
        $form = $this->createDeleteForm($report->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getReportManager()->removeReport($report->getId());

            $this->addFlash(
                'success',
                $this->get('translator')->trans('report.delete.success')
            );
        }

        return $this->redirectToRoute('report_header');
    }

    /**
     * Creates a form to delete a Report entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('report_delete', ['id' => $id]))
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
     * @param Report $report
     * @return ReportDefinition
     */
    private function getReportDefinition(Report $report)
    {
        return $this->getSerializer()->deserialize(
            $report->getDefinition(),
            'Fitch\TutorBundle\Model\ReportDefinition',
            'json'
        );
    }

    /**
     * @param ReportDefinition $reportDefinition
     * @return \Fitch\TutorBundle\Entity\Tutor[]
     */
    private function getReportData(ReportDefinition $reportDefinition)
    {
        return $this->getTutorManager()->getReportData($reportDefinition);
    }

    /**
     * Produces a Downloadable Excel Report entity. Lordy!
     *
     * @Route("/excel/{id}", requirements={"id" = "\d+"}, name="report_excel")
     * @Method("GET")
     *
     * @param Report $report
     *
     * @return StreamedResponse
     */
    public function downloadExcelAction(Report $report)
    {
        $reportDefinition = $this->getReportDefinition($report);
        $data = $this->getReportData($reportDefinition);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter(
            $reportDefinition->createPHPExcelObject($this->get('phpexcel'), $this->getUser(), $report, $data),
            'Excel5'
        );
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=stream-file.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
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

    /**
     * @return LanguageManager
     */
    private function getLanguageManager()
    {
        return $this->get('fitch.manager.language');
    }

    /**
     * @return ReportManager
     */
    private function getReportManager()
    {
        return $this->get('fitch.manager.report');
    }

    /**
     * @return SerializerInterface
     */
    private function getSerializer()
    {
        return $this->get('jms_serializer');
    }

}