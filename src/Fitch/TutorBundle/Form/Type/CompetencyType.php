<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\TutorBundle\Model\CompetencyLevelManager;
use Fitch\TutorBundle\Model\CompetencyTypeManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class CompetencyType extends AbstractType
{
    /** @var  Translator */
    protected $translator;

    /** @var  CompetencyTypeManager */
    protected $competencyTypeManager;

    /** @var  CompetencyLevelManager */
    protected $competencyLevelManager;

    public function __construct(
        TranslatorInterface $translator,
        CompetencyTypeManager $competencyTypeManager,
        CompetencyLevelManager $competencyLevelManager
    ) {
        $this->translator = $translator;
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
        return 'fitch_tutorbundle_competency';
    }
}
