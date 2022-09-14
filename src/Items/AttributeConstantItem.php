<?php

namespace Bfg\Attributes\Items;

use ReflectionClass;
use ReflectionClassConstant;

class AttributeConstantItem extends AttributeItem
{
    public function __construct(
        public object $attribute,
        public ?ReflectionClass $ref,
        public ?ReflectionClassConstant $constant = null,
    ) {
    }
}
