<?php

namespace Fitch\EntityAttributeValueBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

class CompleteAttributeType extends AttributeType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('definition', new DefinitionType(), array(

        ));
    }
}
