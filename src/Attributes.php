<?php

namespace Bfg\Attributes;

use Bfg\Attributes\Items\AttributeItem;
use Bfg\Attributes\Scanner\ScanClasses;
use Bfg\Attributes\Scanner\ScanDirectories;
use Bfg\Attributes\Scanner\ScanFiles;
use Bfg\Attributes\Targets\ClassConstantTarget;
use Bfg\Attributes\Targets\ClassTarget;
use Bfg\Attributes\Targets\GlobalTarget;
use Bfg\Attributes\Targets\MethodTarget;
use Bfg\Attributes\Targets\PropertyTarget;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionException;

/**
 * Class Attributes
 * @package Bfg\Attributes
 */
class Attributes
{
    /**
     * @var string|null
     */
    public ?string $path = null;

    /**
     * @var Collection|null
     */
    public ?Collection $classes = null;

    /**
     * @param  string  $path
     * @return $this
     */
    public function inPath(
        string $path
    ): static {
        $this->path = $path;
        return $this;
    }

    /**
     * @param  string  $class
     * @return $this
     * @throws ReflectionException
     */
    public function inClass(
        string $class
    ): static {

        $this->classes = collect([new ReflectionClass($class)]);

        return $this;
    }

    /**
     * @param  string  $attribute
     * @param  callable  $event
     * @param  int  $target_on
     * @return int
     */
    public function find(
        string $attribute,
        callable $event,
        int $target_on = \Attribute::TARGET_ALL
    ): int {

        $attributes = $this->getAttributes($attribute, $target_on);

        foreach ($attributes as $attribute) {

            app()->call($event, $attribute->toArray());
        }
        return count($attributes);
    }

    /**
     * @param  string  $attribute
     * @param  int  $target_on
     * @return AttributeItem[]|Collection
     */
    public function getAttributes(
        string $attribute,
        int $target_on = \Attribute::TARGET_ALL
    ): Collection {

        $classes = $this->classes();

        if ($target_on === \Attribute::TARGET_CLASS) {
            $attributes = (new ClassTarget())->run($classes, $attribute);
        } else if ($target_on === \Attribute::TARGET_PROPERTY) {
            $attributes = (new PropertyTarget())->run($classes, $attribute);
        } else if ($target_on === \Attribute::TARGET_CLASS_CONSTANT) {
            $attributes = (new ClassConstantTarget())->run($classes, $attribute);
        } else if ($target_on === \Attribute::TARGET_METHOD) {
            $attributes = (new MethodTarget())->run($classes, $attribute);
        } else {
            $attributes = (new GlobalTarget())->run($classes, $attribute);
        }

        return collect($attributes);
    }

    /**
     * @return Collection|ReflectionClass[]
     */
    public function classes(): Collection
    {
        if ($this->classes) {
            return $this->classes;
        } else if (!$this->path) {
            return app(ScanClasses::class)->classes;
        } else {
            $fs = app(Filesystem::class);
            return (new ScanClasses(
                $fs,
                new ScanFiles(
                    $fs,
                    new ScanDirectories(
                        $fs,
                        $this->path
                    )
                )
            ))->classes;
        }
    }
}
