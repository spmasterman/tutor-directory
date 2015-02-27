<?php

namespace Fitch\EntityAttributeValueBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Fitch\EntityAttributeValueBundle\Form\EventListener\AttributeSubscriber;

/**
 * Class AttributeType.
 */
class AttributeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $subscriber = new AttributeSubscriber($builder->getFormFactory());
        $builder->addEventSubscriber($subscriber);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fitch\EntityAttributeValueBundle\Entity\Attribute',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'attribute';
    }
}
