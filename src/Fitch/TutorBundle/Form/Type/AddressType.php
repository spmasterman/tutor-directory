<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\TutorBundle\Model\CountryManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\Translator;

class AddressType extends AbstractType
{
    /** @var  Translator  */
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
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'text', [
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('address.type.placeholder')],
                'required' => false,
            ])
            ->add('streetPrimary', 'text', [
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('address.street_primary.placeholder')],
                'required' => false,
            ])
            ->add('streetSecondary', 'text', [
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('address.street_secondary.placeholder')],
                'required' => false,
            ])
            ->add('city', 'text', [
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('address.city.placeholder')],
                'required' => false,
            ])
            ->add('state', 'text', [
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('address.state.placeholder')],
                'required' => false,
            ])
            ->add('zip', 'text', [
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('address.zip.placeholder')],
                'required' => false,
            ])
            ->add('country', 'entity', [
                'class' => 'FitchTutorBundle:Country',
                'label' => false,
                'choices' => $this->countryManager->buildChoicesForAddress(),
                'preferred_choices' => $this->countryManager->buildPreferredChoicesForAddress(),
                'property' => 'name',
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fitch\TutorBundle\Entity\Address',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fitch_tutorbundle_address';
    }
}
