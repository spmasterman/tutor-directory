<?php

namespace Fitch\EntityAttributeValueBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Fitch\EntityAttributeValueBundle\Form\EventListener\AttributeSubscriber;

class AttributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $subscriber = new AttributeSubscriber($builder->getFormFactory());
        $builder->addEventSubscriber($subscriber);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fitch\EntityAttributeValueBundle\Entity\Attribute',
        ));
    }

    public function getName()
    {
        return 'attribute';
    }
}
