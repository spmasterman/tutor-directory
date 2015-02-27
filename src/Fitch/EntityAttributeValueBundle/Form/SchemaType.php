<?php

namespace Fitch\EntityAttributeValueBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class SchemaType.
 */
class SchemaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = null;

        $builder->add('definitions', 'collection', [
            'type' => new DefinitionType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
        ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fitch\EntityAttributeValueBundle\Entity\Schema',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'schema';
    }
}
