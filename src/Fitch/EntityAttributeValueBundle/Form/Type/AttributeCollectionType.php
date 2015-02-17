<?php

namespace Fitch\EntityAttributeValueBundle\Form\Type;

use Fitch\EntityAttributeValueBundle\Form\DataTransformer\AttributesToGroupedAttributesTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AttributeCollectionType extends CollectionType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $transformer = new AttributesToGroupedAttributesTransformer($options['group']);
        $builder->addModelTransformer($transformer);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'group' => ''
        ]);
        parent::setDefaultOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'attributeCollection';
    }
}
