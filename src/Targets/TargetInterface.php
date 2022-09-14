<?php

namespace Bfg\Attributes\Targets;

use Illuminate\Support\Collection;

abstract class TargetInterface
{
    /**
     * @param  Collection  $classes
     * @param  string  $attribute
     * @return array
     */
    abstract public function run(Collection $classes, string $attribute): array;

    /**
     * @param  $property
     * @param  string  $attributeClass
     * @return array
     */
    protected function getAttributes($property, string $attributeClass): array
    {
        $attributes = $property->getAttributes($attributeClass, \ReflectionAttribute::IS_INSTANCEOF);

        foreach ($attributes as $key => $attribute) {

            $attributes[$key] = $attribute->newInstance();
        }

        return $attributes;
    }
}
