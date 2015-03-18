<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\FrontEndBundle\Form\Type\OnOffType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class FileTypeType.
 */
class FileTypeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('default', new OnOffType(), [
                'type' => 'yesno',
                'required' => false,
            ])
            ->add('private', new OnOffType(), [
                'type' => 'yesno',
                'required' => false,
            ])
            ->add('suitableForProfilePicture', new OnOffType(), [
                'type' => 'yesno',
                'required' => false,
                'label' => 'Use as Profile Picture',
            ])
            ->add('displayWithBio', new OnOffType(), [
                'type' => 'yesno',
                'required' => false,
                'label' => 'Display as part of Bio',
            ])

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fitch\TutorBundle\Entity\FileType',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fitch_tutorbundle_filetype';
    }
}
