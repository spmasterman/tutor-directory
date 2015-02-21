<?php
namespace Fitch\FrontEndBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OnOffType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array('type'));
    }

    /**
     * Pass the inner_class option to the view.
     *
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('type', $options)) {
            $view->vars['type'] = $options['type'];
        }
    }

    public function getParent()
    {
        return 'checkbox';
    }

    public function getName()
    {
        return 'onoff';
    }
}
