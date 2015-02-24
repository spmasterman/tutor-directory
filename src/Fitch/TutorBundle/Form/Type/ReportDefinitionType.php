<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\TutorBundle\Model\CategoryManager;
use Fitch\TutorBundle\Model\CompetencyLevelManager;
use Fitch\TutorBundle\Model\CompetencyTypeManager;
use Fitch\TutorBundle\Model\CurrencyManager;
use Fitch\TutorBundle\Model\LanguageManager;
use Fitch\TutorBundle\Model\RateManager;
use Fitch\TutorBundle\Model\ReportDefinition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class ReportDefinitionType extends AbstractType
{
    /** @var  Translator */
    protected $translator;

    /** @var  CurrencyManager  */
    protected $currencyManager;

    /** @var  RateManager  */
    protected $rateManager;

    /** @var  CategoryManager */
    protected $categoryManager;

    /** @var  CompetencyTypeManager */
    protected $competencyTypeManager;

    /** @var  CompetencyLevelManager */
    protected $competencyLevelManager;

    /** @var  LanguageManager */
    protected $languageManager;

    public function __construct(
        TranslatorInterface $translator,
        CurrencyManager $currencyManager,
        RateManager $rateManager,
        CategoryManager $categoryManager,
        CompetencyTypeManager $competencyTypeManager,
        CompetencyLevelManager $competencyLevelManager,
        LanguageManager $languageManager
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
