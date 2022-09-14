<?php

namespace Bfg\Attributes\Targets;

use Bfg\Attributes\Items\AttributeClassItem;
use Illuminate\Support\Collection;
use ReflectionClass;

class ClassTarget extends TargetInterface
{
    /**
     * @param  Collection  $classes
     * @param  string  $attribute
     * @return array
     */
    public function run(Collection $classes, string $attribute): array
    {
        return $classes->map(function (ReflectionClass $class) use ($attribute) {
            if ($attrs = $this->getAttributes($class, $attribute)) {
                $classes = [];
                foreach ($attrs as $attr) {

                    $classes[] = new AttributeClassItem(
                        attribute: $attr,
                        ref: $class,
                        class: $class,
                    );
                }
                return $classes;
            }
            return null;
        })->filter()->collapse()->toArray();
    }
}
