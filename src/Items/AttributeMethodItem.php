<?php

namespace Bfg\Attributes\Items;

use ReflectionClass;
use ReflectionMethod;

class AttributeMethodItem extends AttributeItem
{
    public function __construct(
        public object $attribute,
        public ?ReflectionClass $ref,
        public ?ReflectionMethod $method = null,
    ) {
    }
}
