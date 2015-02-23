<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\TutorBundle\Model\BusinessAreaManager;
use Fitch\TutorBundle\Model\CompetencyLevelManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class BusinessAreaFilterType extends AbstractType
{
    /** @var  Translator */
    protected $translator;

    /** @var  BusinessAreaManager */
    protected $businessAreaManager;

    public function __construct(
        TranslatorInterface $translator,
        BusinessAreaManager  $businessAreaManager
    ) {
        $this->translator = $translator;
        $this->businessAreaManager = $businessAreaManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('business_area', 'entity', [
                'class' => 'Fitch\TutorBundle\Entity\BusinessArea',
                'expanded' => false,
                'multiple' => true,
                'choices' => $this->businessAreaManager->buildChoices(),
                'placeholder' => 'Filter by Business Area...',
                'required' => false,
                'label' => 'Business Area',
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
                    'and' => 'All selected Business Area',
                    'or' => 'Any selected Business Areas'
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
        return 'fitch_tutorbundle_business_area_filter';
    }
}
