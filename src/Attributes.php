<?php

namespace Bfg\Attributes;

use Attribute;
use Bfg\Attributes\Items\AttributeClassItem;
use Bfg\Attributes\Items\AttributeConstantItem;
use Bfg\Attributes\Items\AttributeItem;
use Bfg\Attributes\Items\AttributeMethodItem;
use Bfg\Attributes\Items\AttributePropertyItem;
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
    protected ?string $path = null;

    /**
     * @var Collection|null
     */
    protected ?Collection $classes = null;

    /**
     * @var string|null
     */
    protected ?string $target_on = null;

    /**
     * @var string|null
     */
    protected ?string $attribute = null;

    protected static ?Collection $cacheClass = null;

    protected static array $cachePaths = [];

    /**
     * @param  string  $path
     * @return $this
     */
    public function wherePath(
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
    public function whereClass(
        string $class
    ): static {

        $this->classes = collect([new ReflectionClass($class)]);

        return $this;
    }

    /**
     * @return $this
     */
    public function whereTargetClass(): static
    {
        $this->target_on = Attribute::TARGET_CLASS;

        return $this;
    }

    /**
     * @return $this
     */
    public function whereTargetProperty(): static
    {
        $this->target_on = Attribute::TARGET_PROPERTY;

        return $this;
    }

    /**
     * @return $this
     */
    public function whereTargetConstant(): static
    {
        $this->target_on = Attribute::TARGET_CLASS_CONSTANT;

        return $this;
    }

    /**
     * @return $this
     */
    public function whereTargetMethod(): static
    {
        $this->target_on = Attribute::TARGET_METHOD;

        return $this;
    }

    /**
     * @return $this
     */
    public function whereAttribute(?string $attributeClass): static
    {
        $this->attribute = $attributeClass;

        return $this;
    }





    /**
     * @param  callable  $event
     * @return int
     */
    public function map(
        callable $event,
    ): int {

        $attributes = $this->all();

        foreach ($attributes as $attribute) {

            call_user_func($event, $attribute);
        }
        return count($attributes);
    }

    /**
     * @return AttributeItem[]|AttributePropertyItem[]|AttributeMethodItem[]|AttributeClassItem[]|AttributeConstantItem[]|Collection
     */
    public function all(): Collection {

        $classes = $this->classes();

        $target_on = $this->target_on ?: Attribute::TARGET_ALL;

        if (! $this->attribute) {
            $attributes = [];
        } else if ($target_on === Attribute::TARGET_CLASS) {
            $attributes = (new ClassTarget())->run($classes, $this->attribute);
        } else if ($target_on === Attribute::TARGET_PROPERTY) {
            $attributes = (new PropertyTarget())->run($classes, $this->attribute);
        } else if ($target_on === Attribute::TARGET_CLASS_CONSTANT) {
            $attributes = (new ClassConstantTarget())->run($classes, $this->attribute);
        } else if ($target_on === Attribute::TARGET_METHOD) {
            $attributes = (new MethodTarget())->run($classes, $this->attribute);
        } else {
            $attributes = (new GlobalTarget())->run($classes, $this->attribute);
        }

        return collect($attributes);
    }

    /**
     * @param  callable|null  $callback
     * @return AttributeItem[]|AttributePropertyItem[]|AttributeMethodItem[]|AttributeClassItem[]|AttributeConstantItem[]|Collection
     */
    public function filter(callable $callback = null): Collection
    {
        return $this->all()->filter($callback);
    }

    /**
     * @return Collection|ReflectionClass[]
     */
    public function classes(): Collection
    {
        if ($this->classes) {
            return $this->classes;
        } else if (!$this->path) {
            if (static::$cacheClass) {
                return static::$cacheClass;
            }
            return static::$cacheClass = app(ScanClasses::class)->classes;
        } else {
            if (! is_dir($this->path)) {
                return collect();
            }
            if (isset(static::$cachePaths[$this->path])) {
                return static::$cachePaths[$this->path];
            }
            $fs = app(Filesystem::class);
            return static::$cachePaths[$this->path] = (new ScanClasses(
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

    public static function new(string $attribute = null): static
    {
        return (new static())->whereAttribute($attribute);
    }
}
