<?php

namespace Fitch\TutorBundle\Controller;

use Fitch\TutorBundle\Entity\Report;
use Fitch\TutorBundle\Form\Type\ReportDefinitionType;
use Fitch\TutorBundle\Form\Type\ReportType;
use Fitch\TutorBundle\Model\CategoryManager;
use Fitch\TutorBundle\Model\CompetencyLevelManager;
use Fitch\TutorBundle\Model\CompetencyTypeManager;
use Fitch\TutorBundle\Model\CurrencyManager;
use Fitch\TutorBundle\Model\LanguageManager;
use Fitch\TutorBundle\Model\RateManager;
use Fitch\TutorBundle\Model\Interfaces\RateManagerInterface;
use Fitch\TutorBundle\Model\ReportDefinition;
use Fitch\TutorBundle\Model\ReportManager;
use Fitch\TutorBundle\Model\TutorManager;
use InvalidArgumentException;
use JMS\Serializer\SerializerInterface;
use Liuggio\ExcelBundle\Factory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Translation\TranslatorInterface;

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
     * @Route("list", name="report_list")
     *
     * @Method("GET")
     * @Security("has_role('ROLE_CAN_VIEW_SAVED_REPORTS')")
     *
     * @Template()
     */
    public function listAction()
    {
        return [
            'reports' => $this->getReportManager()->findAll(),
        ];
    }

    /**
     * Lists all Report entities.
     *
     * @Route("", name="report_header")
     *
     * @Method("GET")
     * @Security("has_role('ROLE_CAN_VIEW_SAVED_REPORTS')")
     *
     * @Template()
     */
    public function headerAction()
    {
        if (!$this->isGranted('ROLE_CAN_VIEW_SAVED_REPORTS')) {
            throw new AccessDeniedHttpException('Unauthorised access!');
        }

        return [
            'reports' => $this->getReportManager()->findAll(),
            'form' => $this->createReportForm()->createView(),
        ];
    }

    /**
     * View an ad hoc report.
     *
     * @Route("/view", name="report_view")
     *
     * @Method("GET")
     *
     * @Template()
     * @Security("has_role('ROLE_CAN_CREATE_AD_HOC_REPORTS')")
     *
     * @param Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $form = $this->createReportForm();
        $form->handleRequest($request);

        $reportDefinition = new ReportDefinition($form, $this->isGranted('ROLE_CAN_ACCESS_SENSITIVE_DATA'));

        $report = $this->getReportManager()->createReport();
        $report->setDefinition($this->getSerializer()->serialize($reportDefinition, 'json'));

        return [
            'form' => $form->createView(),
            'saveForm' => $this->createCreateForm($report)->createView(),
            'data' => $this->getReportData($reportDefinition),
            'definition' => $reportDefinition,
            'unrestricted' => $this->isGranted('ROLE_CAN_ACCESS_SENSITIVE_DATA'),
        ];
    }

    /**
     * Show a pre saved report.
     *
     * @Route("/show/{id}", name="report_show")
     *
     * @Method("GET")
     *
     * @Template()
     * @Security("has_role('ROLE_CAN_VIEW_SAVED_REPORTS')")
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
            'unrestricted' => $this->isGranted('ROLE_CAN_ACCESS_SENSITIVE_DATA'),
            'report' => $report,
        ];
    }

    /**
     * Creates a form to handle the report.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createReportForm()
    {
        /** @var TranslatorInterface $translator */
        $translator = $this->get('translator');

        //$form = $this->createForm(
        $form =  $this->get('form.factory')->createNamedBuilder(
            'ftbr',
            new ReportDefinitionType(
                $translator,
                $this->getCurrencyManager(),
                $this->getRateManager(),
                $this->getCategoryManager(),
                $this->getCompetencyTypeManager(),
                $this->getCompetencyLevelManager(),
                $this->getLanguageManager()
            ),
            null,
            [
                'action' => $this->generateUrl('report_view'),
                'method' => 'GET',
                'csrf_protection' => false,
            ]
        )->getForm();

        /** @var $form FormInterface */
        $form->add(
            'submit',
            'submit',
            [
                'label' => 'View Report',
                'attr' => [
                    'submit_class' => 'btn-success',
                    'submit_glyph' => 'fa-arrow-circle-right',
                ],
            ]
        );

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

        $form->add(
            'submit',
            'submit',
            [
                'label' => 'Update',
                'attr' => [
                    'submit_class' => 'btn-success',
                    'submit_glyph' => 'fa-plus-circle',
                ],
            ]
        );

        return $form;
    }

    /**
     * Creates a new report entity.
     *
     * @Route("/", name="report_create")
     *
     * @Method("POST")
     *
     * @Template("FitchTutorBundle:Status:new.html.twig")
     * @Security("has_role('ROLE_CAN_CREATE_SAVED_REPORTS')")
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
     *
     * @Method("GET")
     *
     * @Template()
     * @Security("has_role('ROLE_CAN_CREATE_SAVED_REPORTS')")
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
     *
     * @Method("PUT")
     *
     * @Template("FitchTutorBundle:Report:edit.html.twig")
     * @Security("has_role('ROLE_CAN_CREATE_SAVED_REPORTS')")
     *
     * @param Request $request
     * @param Report  $report
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Report $report)
    {
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
     *
     * @Method("DELETE")
     *
     * @Security("has_role('ROLE_CAN_CREATE_SAVED_REPORTS')")
     *
     * @param Request $request
     * @param Report  $report
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
     * @param Report $report
     *
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
     *
     * @return \Fitch\TutorBundle\Entity\Tutor[]
     */
    private function getReportData(ReportDefinition $reportDefinition)
    {
        return $this->getTutorManager()->getReportData($reportDefinition);
    }

    /**
     * Produces a Downloadable Excel Report entity. Lordy!
     *
     * @Route("/download/{format}/{id}", requirements={"id" = "\d+"}, name="report_download")
     *
     * @Method("GET")
     *
     * @Security("has_role('ROLE_CAN_VIEW_SAVED_REPORTS')")
     *
     * @param Report $report
     * @param $format
     *
     * @return StreamedResponse
     *
     * @throws \Exception
     */
    public function downloadAction(Report $report, $format)
    {
        $reportDefinition = $this->getReportDefinition($report);
        $data = $this->getReportData($reportDefinition);

        // generate filename (remove bad filename characters from the reportName
        $filename = preg_replace("([^\w\s\d\-_~\[\]\(\).])", '', $report->getName());
        // Remove any runs of periods
        $filename = preg_replace("([\.]{2,})", '', $filename);

        // Setup the bits that vary by {format}
        switch ($format) {
            case 'excel':
                $fileFormat = 'Excel2007';
                $filename = "TrainerReport-{$filename}.xls";
                $contentType = "text/vnd.ms-excel; charset=utf-8";
                break;
            case 'csv':
                $fileFormat = 'CSV';
                $filename = "TrainerReport-{$filename}.csv";
                $contentType = "text/csv";
                break;
            case 'pdf':
                $fileFormat = 'PDF';
                $filename = "TrainerReport-{$filename}.pdf";
                $contentType = "application/pdf";

                $rendererName = \PHPExcel_Settings::PDF_RENDERER_TCPDF;
                $rendererLibraryPath = $this->get('kernel')->getRootDir().'/../vendor/tecnick.com/tcpdf';

                if (!\PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                    throw new \Exception('PDF Renderer is not properly installed');
                }
                break;
            default:
                throw new InvalidArgumentException($format.' is not a valid download type.');
        }

        // create the writer
        $writer = $this->getExcelFactory()->createWriter(
            $reportDefinition->createPHPExcelObject(
                $this->getExcelFactory(),
                $this->getUser(),
                $report,
                $data,
                $this->isGranted('ROLE_CAN_ACCESS_SENSITIVE_DATA')
            ),
            $fileFormat
        );

        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );

        // adding headers
        $response->headers->set('Content-Type', $contentType);
        $response->headers->set('Content-Disposition', "attachment;filename={$filename}");
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }

    /**
     * @return RateManagerInterface
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
     * @return CategoryManager
     */
    private function getCategoryManager()
    {
        return $this->get('fitch.manager.category');
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

    /**
     * @return Factory
     */
    private function getExcelFactory()
    {
        return $this->get('phpexcel');
    }
}
