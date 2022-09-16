<?php

namespace Bfg\Attributes\Items;

use ArrayAccess;

abstract class AttributeItem implements ArrayAccess
{
    public function offsetExists(mixed $offset): bool
    {
        return property_exists($this, $offset);
    }

    public function offsetGet(mixed $offset)
    {
        return $this->{$offset};
    }

    public function offsetSet(mixed $offset, mixed $value)
    {
        $this->{$offset} = $value;
    }

    public function offsetUnset(mixed $offset)
    {

    }
}
