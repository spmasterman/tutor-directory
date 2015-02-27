<?php

namespace Fitch\EntityAttributeValueBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CompleteAttributeType.
 */
class CompleteAttributeType extends AttributeType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('definition', new DefinitionType(), array(

        ));
    }
}
