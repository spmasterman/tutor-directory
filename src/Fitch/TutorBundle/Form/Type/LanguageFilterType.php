<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\TutorBundle\Model\LanguageManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class LanguageFilterType.
 */
class LanguageFilterType extends AbstractType
{
    /** @var  Translator */
    protected $translator;

    /** @var  LanguageManagerInterface */
    protected $languageManager;

    /**
     * @param TranslatorInterface      $translator
     * @param LanguageManagerInterface $languageManager
     */
    public function __construct(
        TranslatorInterface $translator,
        LanguageManagerInterface $languageManager
    ) {
        $this->translator = $translator;
        $this->languageManager = $languageManager;
    }

    /**
     * {@inheritdoc}
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
                    'or' => 'Any selected Language',
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
