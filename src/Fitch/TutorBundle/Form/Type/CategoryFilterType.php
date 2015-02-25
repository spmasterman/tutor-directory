<?php

namespace Fitch\TutorBundle\Form\Type;

use Fitch\TutorBundle\Model\Interfaces\CategoryManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class CategoryFilterType extends AbstractType
{
    /** @var  Translator */
    protected $translator;

    /** @var  CategoryManagerInterface */
    protected $categoryManager;

    public function __construct(
        TranslatorInterface $translator,
        CategoryManagerInterface  $categoryManager
    ) {
        $this->translator = $translator;
        $this->categoryManager = $categoryManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', 'entity', [
                'class' => 'Fitch\TutorBundle\Entity\Category',
                'expanded' => false,
                'multiple' => true,
                'choices' => $this->categoryManager->buildChoices(),
                'placeholder' => 'Filter by Category...',
                'required' => false,
                'label' => 'Category',
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
                    'and' => 'All selected Categories',
                    'or' => 'Any selected Categories'
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
        return 'fitch_tutorbundle_category_filter';
    }
}
