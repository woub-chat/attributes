<?php

namespace Bfg\Attributes\Items;

use ReflectionClass;
use ReflectionProperty;

class AttributePropertyItem extends AttributeItem
{
    public function __construct(
        public object $attribute,
        public ?ReflectionClass $ref,
        public ?ReflectionProperty $property = null,
    ) {
    }
}
