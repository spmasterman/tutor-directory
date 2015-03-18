<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\TutorBundle\Model\CountryManager;
use Fitch\TutorBundle\Model\CountryManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class TutorType.
 */
class TutorType extends AbstractType
{
    /** @var  Translator */
    protected $translator;

    /** @var  CountryManager  */
    protected $countryManager;

    /**
     * @param TranslatorInterface     $translator
     * @param CountryManagerInterface $countryManager
     */
    public function __construct($translator, $countryManager)
    {
        $this->translator = $translator;
        $this->countryManager = $countryManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('company')
            ->add('region')
            ->add('status')
            ->add('tutorType', null, [
                'label' => 'Trainer Type',
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fitch\TutorBundle\Entity\Tutor',
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
