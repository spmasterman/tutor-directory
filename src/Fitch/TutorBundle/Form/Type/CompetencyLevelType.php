<?php

namespace Fitch\TutorBundle\Form\Type;

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
        $builder
            ->add('name')
            ->add('color', 'choice', [
                'choices'   => [
                    '#cccccc' => 'Light Grey',
                    '#db8c8b' => 'Pastel Red',
                    '#daac8a' => 'Pastel Orange',
                    '#d9cd88' => 'Pastel Yellow',
                    '#c6da88' => 'Pastel Lichen',
                    '#a5da88' => 'Pastel Lime',
                    '#89da90' => 'Pastel Green',
                    '#89dab2' => 'Pastel Aqua',
                    '#89d9d3' => 'Pastel Turqoise',
                    '#8abfda' => 'Pastel Blue',
                    '#8b9edb' => 'Pastel Navy',
                    '#998adb' => 'Pastel Indigo',
                    '#ba8adb' => 'Pastel Violet',
                ],
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
