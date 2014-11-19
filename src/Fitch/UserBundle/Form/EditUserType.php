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
            ->add('email', 'email')
            ->add('roles', 'choice',[
                'choices' => [
                    //   'ROLE_USER' => 'Read Only user',
                    'ROLE_EDITOR' => 'Edit PUBLIC tutor details',
                    'ROLE_ADMIN' => 'Edit ALL tutor details',
                    'ROLE_SUPER_ADMIN' => 'Full Access (Including User Management)',
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Additional Roles',
                'attr' => [
                    'class' => "control-inline simple-checkbox",
                ]
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
