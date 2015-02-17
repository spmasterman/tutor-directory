<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\TutorBundle\Model\CompetencyLevelManager;
use Fitch\TutorBundle\Model\CompetencyTypeManager;
use Fitch\TutorBundle\Model\CurrencyManager;
use Fitch\TutorBundle\Model\RateManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class ReportType extends AbstractType
{
    /** @var  Translator */
    protected $translator;

    /** @var  CurrencyManager  */
    protected $currencyManager;

    /** @var  RateManager  */
    protected $rateManager;

    /** @var  CompetencyTypeManager */
    protected $competencyTypeManager;

    /** @var  CompetencyLevelManager */
    protected $competencyLevelManager;

    public function __construct(
        TranslatorInterface $translator,
        CurrencyManager $currencyManager,
        RateManager $rateManager,
        CompetencyTypeManager $competencyTypeManager,
        CompetencyLevelManager $competencyLevelManager
    ) {
        $this->translator = $translator;
        $this->rateManager = $rateManager;
        $this->currencyManager = $currencyManager;
        $this->competencyTypeManager = $competencyTypeManager;
        $this->competencyLevelManager = $competencyLevelManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
                'required' => false
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
                'required' => false
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
                'required' => false
            ])
            ->add('language', 'entity', [
                'class' => 'FitchTutorBundle:Language',
                'property' => 'name',
                'placeholder' => 'Filter by Language...',
                'required' => false
            ])
            ->add(
                'rate',
                new RateType($this->translator, $this->currencyManager, $this->rateManager),
                [
                    'attr' => ['class' => 'inline-subform',],
                ]
            )
            ->add(
                'competency',
                new CompetencyType($this->translator, $this->competencyTypeManager, $this->competencyLevelManager),
                [
                    'attr' => ['class' => 'inline-subform'],
                    'label' => 'Skill'
                ]
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
//        $resolver->setDefaults(array(
//            'data_class' => 'Fitch\TutorBundle\Entity\Tutor'
//        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fitch_tutorbundle_report';
    }
}
