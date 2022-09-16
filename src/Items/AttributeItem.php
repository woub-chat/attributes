<?php

namespace Bfg\Attributes\Items;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;

abstract class AttributeItem implements ArrayAccess, Arrayable
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

    public function toArray(): array
    {
        return collect(get_class_vars(static::class))
            ->mapWithKeys(
                fn ($prop) => [$prop => $this->{$prop}]
            )->toArray();
    }
}
