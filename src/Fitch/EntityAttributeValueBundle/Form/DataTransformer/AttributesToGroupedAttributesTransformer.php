<?php

namespace Fitch\EntityAttributeValueBundle\Form\DataTransformer;

use Doctrine\ORM\PersistentCollection;
use Fitch\EntityAttributeValueBundle\Entity\Attribute;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class AttributesToGroupedAttributesTransformer implements DataTransformerInterface
{
    /** @var string */
    private $group;

    private $originalAttributes;

    /**
     * @param string $group
     */
    public function __construct($group)
    {
        $this->group = $group;
    }

    /**
     * Transforms Attributes to a subset.
     *
     * @param Attribute[] $attributes
     *
     * @return Attribute[]
     */
    public function transform($attributes)
    {
        $this->originalAttributes = $attributes;

        if ($this->group) {
            $subset = [];
            foreach ($attributes as $attribute) {
                $definition = $attribute->getDefinition();
                if ($definition && $definition->getGroup() == $this->group) {
                    $subset[] = $attribute;
                }
            }
        } else {
            $subset = $attributes;
        }

        return $subset;
    }

    /**
     * Transforms a subset of Attributes into the full thing.
     *
     * @param Attribute[] $subset
     *
     * @return Attribute[]
     *
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($subset)
    {
        if ($this->originalAttributes instanceof PersistentCollection) {
            foreach ($subset as $updatedAttribute) {
                $definition = $updatedAttribute->getDefinition();
                if ($definition) {
                    foreach ($this->originalAttributes as $attribute) {
                        if ($attribute->getDefinition()->getName() == $definition->getName()) {
                            $attribute->setValue($updatedAttribute->getValue());
                        }
                    }
                }
            }
        }

        return $this->originalAttributes;
    }
}
