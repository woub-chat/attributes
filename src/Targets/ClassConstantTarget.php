<?php

namespace Bfg\Attributes\Targets;

use Bfg\Attributes\Items\AttributeConstantItem;
use Illuminate\Support\Collection;
use ReflectionClass;

class ClassConstantTarget extends TargetInterface
{
    /**
     * @param  Collection  $classes
     * @param  string  $attribute
     * @return array
     */
    public function run(Collection $classes, string $attribute): array
    {
        return $classes->map(function (ReflectionClass $class) use ($attribute) {
            $constants = [];
            foreach ($class->getReflectionConstants() as $constant) {
                if ($attrs = $this->getAttributes($constant, $attribute)) {
                    foreach ($attrs as $attr) {
                        $constants[] = new AttributeConstantItem(
                            attribute: $attr,
                            ref: $class,
                            constant: $constant,
                        );
                    }
                }
            }
            return $constants;
        })->collapse()->toArray();
    }
}
