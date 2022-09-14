<?php

namespace Bfg\Attributes\Targets;

use Bfg\Attributes\Items\AttributeMethodItem;
use Illuminate\Support\Collection;
use ReflectionClass;

class MethodTarget extends TargetInterface
{
    /**
     * @param  Collection  $classes
     * @param  string  $attribute
     * @return array
     */
    public function run(Collection $classes, string $attribute): array
    {
        return $classes->map(function (ReflectionClass $class) use ($attribute) {
            $methods = [];
            foreach ($class->getMethods() as $method) {
                if ($attrs = $this->getAttributes($method, $attribute)) {
                    foreach ($attrs as $attr) {
                        $methods[] = new AttributeMethodItem(
                            attribute: $attr,
                            ref: $class,
                            method: $method,
                        );
                    }
                }
            }
            return $methods;
        })->collapse()->toArray();
    }
}
