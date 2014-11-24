<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\TutorBundle\Model\CountryManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\Translator;

class TutorType extends AbstractType
{
    /** @var  Translator */
    protected $translator;

    /** @var  CountryManager  */
    protected $countryManager;

    public function __construct($translator, $countryManager)
    {
        $this->translator = $translator;
        $this->countryManager = $countryManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('region')
            ->add('status')
            ->add('tutorType', null, [
                'label' => 'Trainer Type'
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fitch\TutorBundle\Entity\Tutor'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fitch_tutorbundle_tutor';
    }
}
