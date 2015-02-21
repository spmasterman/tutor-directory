<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\TutorBundle\Model\CurrencyManager;
use Fitch\TutorBundle\Model\RateManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class RateType extends AbstractType
{
    /** @var  Translator */
    protected $translator;

    /** @var  CurrencyManager  */
    protected $currencyManager;

    /** @var  RateManager  */
    protected $rateManager;

    public function __construct(
        TranslatorInterface $translator,
        CurrencyManager $currencyManager,
        RateManager $rateManager
    ) {
        $this->translator = $translator;
        $this->rateManager = $rateManager;
        $this->currencyManager = $currencyManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rateType', 'choice', [
                'expanded' => true,
                'multiple' => true,
                'attr' => [
                    'class' => "control-inline simple-checkbox",
                ],
                'choices' => $this->rateManager->buildChoices(),
                'placeholder' => 'Filter by Rate...',
                'required' => false,
                'label' => 'Rate Type',
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('operator', 'choice', [
                'expanded' => false,
                'multiple' => false,
                'choices' => [
                    'lt' => 'Less',
                    'lte' => 'Less/Equal',
                    'eq' => 'Equal',
                    'gte' => 'Greater/Equal',
                    'gt' => 'Greater',
                ],
                'attr' => [
                    'class' => "control-inline",
                ],
                'label_attr' => ['class' => 'sr-only'],
                'placeholder' => '',
                'required' => false,
                'label' => 'is',
            ])
            ->add('amount', 'number', [
                'required' => false,
                'label' => ' ',
                'attr' => [
                    'class' => "control-inline",
                    'size' => 5,
                ],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('currency', 'entity', [
                'class' => 'Fitch\TutorBundle\Entity\Currency',
                'expanded' => false,
                'multiple' => false,
                'choices' => $this->currencyManager->buildChoices(),
                'preferred_choices' => $this->currencyManager->buildPreferredChoices(),
                'placeholder' => 'in Currency...',
                'required' => false,
                'label' => 'in Currency',
                'attr' => [
                    'class' => "control-inline",
                ],
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
        return 'fitch_tutorbundle_rate';
    }
}
