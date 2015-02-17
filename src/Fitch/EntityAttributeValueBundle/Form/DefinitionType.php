<?php

namespace Fitch\EntityAttributeValueBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DefinitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
        ));
        $builder->add('description', 'textarea', array(
            'required' => false
        ));
        $builder->add('group', 'text', array(
            'required' => false
        ));
        $builder->add('type', 'choice', array(
            'choices' => array(
                'integer'   => 'Integer',
                'text'      => 'Text',
                'textarea'  => 'Textarea',
                'choice'    => 'Select',
                'checkbox'  => 'Checkbox',
                'radio'     => 'Radio'
            )
        ));
        $builder->add('unit', 'text', array(
            'required' => false
        ));
        $builder->add('required', 'checkbox', array(
            'required' => false
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fitch\EntityAttributeValueBundle\Entity\Definition',
        ));
    }

    public function getName()
    {
        return 'definition';
    }
}
