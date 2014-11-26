<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\FrontEndBundle\Form\Type\OnOffType;
use Fitch\TutorBundle\Model\CurrencyManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OperatingRegionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CurrencyManager $currencyManager */
        $currencyManager = $options['currencyManager'];

        $builder
            ->add('name')
            ->add('code')
            ->add('default', new OnOffType(), [
                'required' => false,
                'type' => 'yesno'
            ]);

        if ($currencyManager) {
            $builder->add('defaultCurrency', 'entity', [
                'class' => 'Fitch\TutorBundle\Entity\Currency',
                'expanded' => false,
                'multiple' => false,
                'choices' => $currencyManager->buildChoices(),
                'preferred_choices' => $currencyManager->buildPreferredChoices()
            ]);
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fitch\TutorBundle\Entity\OperatingRegion',
            'currencyManager' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fitch_tutorbundle_operatingregion';
    }
}
