<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\TutorBundle\Model\CompetencyLevelManager;
use Fitch\TutorBundle\Model\CompetencyTypeManager;
use Fitch\TutorBundle\Model\CompetencyTypeManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class CompetencyFilterType extends AbstractType
{
    /** @var  Translator */
    protected $translator;

    /** @var  \Fitch\TutorBundle\Model\CompetencyTypeManagerInterface */
    protected $competencyTypeManager;

    public function __construct(
        TranslatorInterface $translator,
        CompetencyTypeManagerInterface $competencyTypeManager
    ) {
        $this->translator = $translator;
        $this->competencyTypeManager = $competencyTypeManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('competencyType', 'entity', [
                'class' => 'Fitch\TutorBundle\Entity\CompetencyType',
                'expanded' => false,
                'multiple' => true,
                'choices' => $this->competencyTypeManager->buildChoices(),
                'placeholder' => 'Filter by Skill Type...',
                'required' => false,
                'label' => 'Skill Type',
                'attr' => [
                    'class' => "control-inline",
                    'size' => 8,
                ],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('combine', 'choice', [
                'placeholder' => false,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'and' => 'All selected Competency Types',
                    'or' => 'Any selected Competency Types'
                ],
                'required' => false,
                'attr' => [
                    'class' => "control-inline simple-radio radio-green stacked-group",
                    'size' => 8,
                ],
                'label_attr' => ['class' => 'sr-only'],
            ])
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fitch_tutorbundle_competency_filter';
    }
}
