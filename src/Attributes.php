<?php

namespace Bfg\Attributes;

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
     * @param  string  $attribute
     * @param  callable  $event
     * @param  int  $target_on
     * @return int
     */
    public function inPath(
        string $path, string $attribute, callable $event, int $target_on = \Attribute::TARGET_ALL
    ): int {
        $p = $this->path;
        $this->path = $path;
        $result = $this->find($attribute, $event, $target_on);
        $this->path = $p;
        return $result;
    }

    /**
     * @param  string  $class
     * @param  string  $attribute
     * @param  callable  $event
     * @param  int  $target_on
     * @return int
     * @throws ReflectionException
     */
    public function inClass(
        string $class, string $attribute, callable $event, int $target_on = \Attribute::TARGET_ALL
    ): int {
        $c = $this->classes;
        $this->classes = collect([new ReflectionClass($class)]);
        $result = $this->find($attribute, $event, $target_on);
        $this->classes = $c;
        return $result;
    }

    /**
     * @param  string  $attribute
     * @param  callable  $event
     * @param  int  $target_on
     * @return int
     */
    public function find(string $attribute, callable $event, int $target_on = \Attribute::TARGET_ALL): int
    {
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

        foreach ($attributes as $attribute) {

            call_user_func_array($event, array_values($attribute));
        }

        return count($attributes);
    }

    /**
     * @return Collection
     */
    protected function classes(): Collection
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
