<?php

namespace Bfg\Attributes\Targets;

use Illuminate\Support\Collection;

class GlobalTarget extends TargetInterface
{
    /**
     * @param  Collection  $classes
     * @param  string  $attribute
     * @return array
     */
    public function run(Collection $classes, string $attribute): array
    {
        return array_merge(
            (new ClassTarget())->run($classes, $attribute),
            (new PropertyTarget())->run($classes, $attribute),
            (new ClassConstantTarget())->run($classes, $attribute),
            (new MethodTarget())->run($classes, $attribute)
        );
    }
}
