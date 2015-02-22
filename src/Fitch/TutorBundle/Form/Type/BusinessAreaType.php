<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\FrontEndBundle\Form\Type\OnOffType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BusinessAreaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('code')
            ->add('prependToCategoryName', new OnOffType(), [
                'label' => 'Prepend this onto all Categories it contains',
                'required' => false,
                'type' => 'yesno',
            ])
            ->add('default', new OnOffType(), [
                'required' => false,
                'type' => 'yesno',
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fitch\TutorBundle\Entity\BusinessArea',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fitch_tutorbundle_business_area';
    }
}
