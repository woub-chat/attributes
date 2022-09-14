<?php

namespace Bfg\Attributes\Items;

use ReflectionClass;

class AttributeClassItem extends AttributeItem
{
    public function __construct(
        public object $attribute,
        public ?ReflectionClass $ref,
        public ?ReflectionClass $class = null,
    ) {
    }
}
