<?php

namespace Fitch\UserBundle\Form\Type;

use Fitch\FrontEndBundle\Form\Type\OnOffType;
use Fitch\UserBundle\Model\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewUserType extends AbstractType
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
            ->add('plainPassword', 'password', [])
            ->add('roles', 'choice',[
                'choices' => Role::getAssignableRolesDictionary(),
                'multiple' => true,
                'expanded' => true,
                'label' => 'Additional Roles',
                'attr' => [
                    'class' => "control-inline simple-checkbox"
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
        return 'fitch_userbundle_user_new';
    }
}
