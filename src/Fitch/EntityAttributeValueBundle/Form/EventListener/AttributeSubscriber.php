<?php
namespace Fitch\EntityAttributeValueBundle\Form\EventListener;

use Fitch\EntityAttributeValueBundle\Entity\Attribute;
use Fitch\EntityAttributeValueBundle\Entity\Option;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class AttributeSubscriber.
 */
class AttributeSubscriber implements EventSubscriberInterface
{
    /** @var FormFactoryInterface */
    private $factory;

    /** @var array */
    private $options;

    /** @var array */
    private $defaultOptions = [
        'allow_expanded' => true,
        'allow_textarea' => true,
        'all_multiple' => false,
    ];

    /**
     * @param FormFactoryInterface $factory
     * @param array                $options
     */
    public function __construct(FormFactoryInterface $factory, $options = array())
    {
        $this->factory = $factory;
        $this->options = $options;
    }

    /**
     * Returns an option or the default
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getOption($name)
    {
        if (!isset($this->options[$name])) {
            $value = $this->defaultOptions[$name];
        } else {
            $value = $this->options[$name];
        }

        return $value;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that we want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data || $data->getDefinition()) {
            return;
        }

        $this->createValueField($data, $form);
    }

    /**
     * @param Attribute     $attribute
     * @param FormInterface $form
     * @param string        $fieldName
     */
    public function createValueField(Attribute $attribute, FormInterface $form, $fieldName = 'value')
    {
        $definition = $attribute->getDefinition();
        $this->options = $definition->getOptions()->toArray();

        list($type, $params, $value) = $this->getNamedParameters(
            $definition->getType(),
            $attribute->getValue()
        );

        $params['required'] = (bool) $definition->isRequired();
        $params['label'] = $definition->getLabel() ?: $definition->getName();

        if ($definition->getUnit() != "") {
            $params['label_attr']['unit'] = $definition->getUnit();
        }

        if ($definition->getDescription() != "") {
            $params['label_attr']['help'] = $definition->getDescription();
        }

        $params['auto_initialize'] = false;

        $form->add($this->factory->createNamed($fieldName, $type, $value, $params));
    }

    /**
     * @param $type
     * @param $value
     *
     * @return array
     */
    private function getNamedParameters($type, $value)
    {
        $params = ['attr' => []];
        if ($type == 'textarea' && !$this->getOption('allow_expanded')) {
            $type = 'text';
        }

        if ($type == 'choice' || $type == 'checkbox' || $type == 'radio') {
            return $this->dealWithChoice($type, $params, $value);
        }

        return array($type, $params, is_array($value) ? null : $value);
    }

    /**
     * @param $type
     * @param $params
     * @param $value
     *
     * @return array
     */
    private function dealWithChoice($type, $params, $value)
    {
        if (($type == 'checkbox' || $type == 'radio') && $this->getOption('allow_expanded')) {
            $params['expanded'] = true;
        }

        if ($this->getOption('all_multiple')) {
            $params['multiple'] = true;
        } else {
            if ($type == 'radio') {
                $params['multiple'] = false;
            } elseif ($type == 'checkbox') {
                if (!is_array($value)) {
                    $value = array(
                        $value => $value,
                    );
                }
                $params['multiple'] = true;
            }
        }

        $params['choices'] = array();
        foreach ($this->options as $option) {
            /* @var Option $option */
            $params['choices'][$option->getName()] = $option->getName();
        }

        $type = 'choice';

        return array($type, $params, $value);
    }
}
