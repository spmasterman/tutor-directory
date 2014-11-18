<?php

namespace Fitch\UserBundle\Form;

use Fitch\FrontEndBundle\Form\Type\OnOffType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditUserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userName')
            ->add('fullName')
            ->add('email')
            ->add('roles', 'choice',[
                'choices' => [
                 //   'ROLE_USER' => 'Read Only user',
                    'ROLE_EDITOR' => 'Read and Edit Non sensitive information',
                    'ROLE_ADMIN' => 'Read and Edit sensitive information',
                    'ROLE_SUPER_ADMIN' => 'Full Access',
                ],
                'multiple' => true,
                'expanded' => true
            ])
            ->add('enabled', new OnOffType(), [
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
            'data_class' => 'Fitch\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fitch_userbundle_user';
    }
}
