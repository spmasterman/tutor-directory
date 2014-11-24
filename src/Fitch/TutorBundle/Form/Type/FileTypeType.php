<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\FrontEndBundle\Form\Type\OnOffType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FileTypeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('default', new OnOffType(), [
                'type' => 'yesno',
                'required' => false
            ])
            ->add('suitableForProfilePicture', new OnOffType(), [
                'type' => 'yesno',
                'required' => false,
                'label' => 'Use as Profile Picture',
            ])
            ->add('private', new OnOffType(), [
                'type' => 'yesno',
                'required' => false
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fitch\TutorBundle\Entity\FileType'
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
