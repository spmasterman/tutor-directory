<?php

namespace Fitch\TutorBundle\Form;

use Fitch\FrontEndBundle\Form\Type\OnOffType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CountryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('twoDigitCode', null, [
                'label' => 'ISO 3661-1 2 Digit Code'
            ])
            ->add('threeDigitCode', null, [
                'label' => 'ISO 3661-1 3 Digit Code'
            ])
            ->add('dialingCode')
            ->add('preferred', new OnOffType(), [
                'required' => false
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
            'data_class' => 'Fitch\TutorBundle\Entity\Country'
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
