<?php
namespace Fitch\FrontEndBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EnhancedTextAreaType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array('helptext'));
        $resolver->setOptional(array('character_count'));
    }

    /**
     * Pass the inner_class option to the view
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('helptext', $options)) {
            $view->vars['helptext'] = $options['helptext'];
        }
    }

    public function getParent()
    {
        return 'textarea';
    }

    public function getName()
    {
        return 'enhancedtextarea';
    }
}
