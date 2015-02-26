<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\TutorBundle\Form\Colors;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompetencyLevelType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = null;
        $builder
            ->add('name')
            ->add('color', 'choice', [
                'choices'   => Colors::getEntityColorsDictionary(),
                'multiple'  => false,
                'expanded'  => false,
                'attr' => [
                    'class' => 'fontawesome',
                ],
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fitch\TutorBundle\Entity\CompetencyLevel',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fitch_tutorbundle_competencylevel';
    }
}
