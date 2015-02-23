<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\TutorBundle\Model\CompetencyLevelManager;
use Fitch\TutorBundle\Model\CompetencyTypeManager;
use Fitch\TutorBundle\Model\LanguageManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class LanguageFilterType extends AbstractType
{
    /** @var  Translator */
    protected $translator;

    /** @var  LanguageManager */
    protected $languageManager;

    public function __construct(
        TranslatorInterface $translator,
        LanguageManager $languageManager
    ) {
        $this->translator = $translator;
        $this->languageManager = $languageManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('language', 'entity', [
                'class' => 'FitchTutorBundle:Language',
                'expanded' => false,
                'multiple' => true,
                'property' => 'name',
                'placeholder' => 'Filter by Language...',
                'choices' => $this->languageManager->buildChoices(),
                'required' => false,
                'attr' => [
                    'class' => "control-inline",
                    'size' => 8,
                ],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('combine', 'choice', [
                'placeholder' => false,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'and' => 'All selected Languages',
                    'or' => 'Any selected Language'
                ],
                'required' => false,
                'attr' => [
                    'class' => "control-inline simple-radio radio-green stacked-group",
                    'size' => 8,
                ],
                'label_attr' => ['class' => 'sr-only'],
            ])
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fitch_tutorbundle_languagefilter';
    }
}
