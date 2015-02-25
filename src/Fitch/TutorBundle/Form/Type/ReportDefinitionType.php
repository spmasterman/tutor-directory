<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\TutorBundle\Model\Interfaces\CategoryManagerInterface;
use Fitch\TutorBundle\Model\Interfaces\CompetencyLevelManagerInterface;
use Fitch\TutorBundle\Model\Interfaces\CompetencyTypeManagerInterface;
use Fitch\TutorBundle\Model\Interfaces\CurrencyManagerInterface;
use Fitch\TutorBundle\Model\Interfaces\LanguageManagerInterface;
use Fitch\TutorBundle\Model\Interfaces\RateManagerInterface;
use Fitch\TutorBundle\Model\ReportDefinition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ReportDefinitionType extends AbstractType
{
    /** @var  TranslatorInterface */
    protected $translator;

    /** @var  CurrencyManagerInterface */
    protected $currencyManager;

    /** @var  RateManagerInterface */
    protected $rateManager;

    /** @var  CategoryManagerInterface */
    protected $categoryManager;

    /** @var  CompetencyTypeManagerInterface */
    protected $competencyTypeManager;

    /** @var  CompetencyLevelManagerInterface */
    protected $competencyLevelManager;

    /** @var  LanguageManagerInterface */
    protected $languageManager;

    public function __construct(
        TranslatorInterface $translator,
        CurrencyManagerInterface $currencyManager,
        RateManagerInterface $rateManager,
        CategoryManagerInterface $categoryManager,
        CompetencyTypeManagerInterface $competencyTypeManager,
        CompetencyLevelManagerInterface $competencyLevelManager,
        LanguageManagerInterface $languageManager
    ) {
        $this->translator = $translator;
        $this->rateManager = $rateManager;
        $this->currencyManager = $currencyManager;
        $this->categoryManager = $categoryManager;
        $this->competencyTypeManager = $competencyTypeManager;
        $this->competencyLevelManager = $competencyLevelManager;
        $this->languageManager = $languageManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = null;

        $builder
            ->add('tutor_type', 'entity', [
                'class' => 'FitchTutorBundle:TutorType',
                'property' => 'name',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => "control-inline simple-checkbox",
                ],
                'placeholder' => 'Filter by Tutor Type...',
                'required' => false,
            ])
            ->add('status', 'entity', [
                'class' => 'FitchTutorBundle:Status',
                'property' => 'name',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => "control-inline simple-checkbox",
                ],
                'placeholder' => 'Filter by Status...',
                'required' => false,
            ])
            ->add('operating_region', 'entity', [
                'class' => 'FitchTutorBundle:OperatingRegion',
                'property' => 'name',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => "control-inline simple-checkbox",
                ],
                'placeholder' => 'Filter by Region...',
                'required' => false,
            ])
            ->add(
                'language',
                new LanguageFilterType($this->translator, $this->languageManager),
                [
                    'attr' => ['class' => 'inline-subform stacked-group'],
                    'label' => 'Language (CTRL selects multiple)',
                ]
            )
            ->add(
                'rate',
                new RateFilterType($this->translator, $this->currencyManager, $this->rateManager),
                [
                    'attr' => ['class' => 'inline-subform stacked-group'],
                    'label' => 'Rate [Restricted]',
                ]
            )
            ->add(
                'category',
                new CategoryFilterType($this->translator, $this->categoryManager),
                [
                    'attr' => ['class' => 'inline-subform stacked-group'],
                    'label' => 'Skill Category (CTRL selects multiple)',
                ]
            )
            ->add(
                'competency',
                new CompetencyFilterType($this->translator, $this->competencyTypeManager),
                [
                    'attr' => ['class' => 'inline-subform stacked-group'],
                    'label' => 'Skill (CTRL selects multiple)',
                ]
            )
            ->add('competencyLevel', 'entity', [
                'class' => 'Fitch\TutorBundle\Entity\CompetencyLevel',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => "control-inline simple-checkbox",
                ],
                'choices' => $this->competencyLevelManager->buildChoices(),
                'placeholder' => 'Filter by Skill Level...',
                'required' => false,
                'label' => 'Skill Level',
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('fields', 'choice', [
                'expanded' => true,
                'multiple' => true,
                'choices' => ReportDefinition::getAvailableFields(),
                'attr' => [
                    'class' => "simple-checkbox",
                ],
                'label_attr' => ['class' => 'sr-only'],
                'placeholder' => '',
                'required' => false,
                'label' => 'Show Columns',
                'data' => ReportDefinition::getDefaultFields(),
            ])
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fitch_tutorbundle_report';
    }
}
