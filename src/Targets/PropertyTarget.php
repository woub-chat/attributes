<?php

namespace Bfg\Attributes\Targets;

use Bfg\Attributes\Items\AttributePropertyItem;
use Illuminate\Support\Collection;
use ReflectionClass;

class PropertyTarget extends TargetInterface
{
    /**
     * @param  Collection  $classes
     * @param  string  $attribute
     * @return array
     */
    public function run(Collection $classes, string $attribute): array
    {
        return $classes->map(function (ReflectionClass $class) use ($attribute) {
            $properties = [];
            foreach ($class->getProperties() as $property) {
                if ($attrs = $this->getAttributes($property, $attribute)) {
                    foreach ($attrs as $attr) {
                        $properties[] = new AttributePropertyItem(
                            attribute: $attr,
                            ref: $class,
                            property: $property
                        );
                    }
                }
            }
            return $properties;
        })->collapse()->toArray();
    }
}
