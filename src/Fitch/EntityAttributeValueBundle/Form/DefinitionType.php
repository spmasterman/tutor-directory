<?php

namespace Fitch\EntityAttributeValueBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DefinitionType.
 */
class DefinitionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', [
        ]);
        $builder->add('description', 'textarea', [
            'required' => false,
        ]);
        $builder->add('group', 'text', [
            'required' => false,
        ]);
        $builder->add('type', 'choice', [
            'choices' => [
                'integer'   => 'Integer',
                'text'      => 'Text',
                'textarea'  => 'Textarea',
                'choice'    => 'Select',
                'checkbox'  => 'Checkbox',
                'radio'     => 'Radio',
            ],
        ]);
        $builder->add('unit', 'text', [
            'required' => false,
        ]);
        $builder->add('required', 'checkbox', [
            'required' => false,
        ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Fitch\EntityAttributeValueBundle\Entity\Definition',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'definition';
    }
}
