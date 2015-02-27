<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\FrontEndBundle\Form\Type\OnOffType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CountryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('twoDigitCode', null, [
                'label' => '2 Digit Code (ISO 3661-1)',
            ])
            ->add('threeDigitCode', null, [
                'label' => '3 Digit Code (ISO 3661-1)',
            ])
            ->add('dialingCode', null, [
                'attr' => [
                    'title' => "Please enter a valid international dialing code (i.e. +44 for the UK, +1 for the US)",
                    'placeholder' => 'A valid international dialing code (i.e. +44 for the UK, +1 for the US)'
                ]
            ])
            ->add('active', new OnOffType(), [
                'type' => 'yesno',
                'required' => false,
            ])
            ->add('preferred', new OnOffType(), [
                'type' => 'yesno',
                'required' => false,
            ])
            ->add('defaultRegion')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fitch\TutorBundle\Entity\Country',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fitch_tutorbundle_country';
    }
}
