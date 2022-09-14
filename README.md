# Extension attributes

## Install
```bash
composer require bfg/attributes
```

## Description
Designed to work with PHP 8 attributes

## About the concept
The concept of this package is to look for 
attributes in classes and perform them.

## Where can I use it?
When we use the PHP attributes as pointers to some actions.
> It is important not to run this process in the 
> overall stream of the user process, since the 
> execution of this process can threaten the time.

## Calling events on attribute
In order to find attributes, we need to specify which 
attribute we will search, by default the package searches 
everywhere except folders `public`,`resources`,`storage`,
`runtimes`,`database` in the project or you can specify 
manually in which folder will look for an attribute.

Consider the global attribute search for the method:
```php
use Bfg\Attributes\Items\AttributeClassItem;
use Bfg\Attributes\Items\AttributeConstantItem;
use Bfg\Attributes\Items\AttributeMethodItem;
use Bfg\Attributes\Items\AttributePropertyItem;
use Bfg\Attributes\Attributes;

Attributes::new(MyAttribute::class)
    ->map(function (AttributePropertyItem|AttributeMethodItem|AttributeClassItem|AttributeConstantItem $item) {
        // Process with my attribute
    });
```
Or the same search but in a specific folder:
```php
use Bfg\Attributes\Items\AttributeClassItem;
use Bfg\Attributes\Attributes;

Attributes::new(MyAttribute::class)
    ->wherePath(app_path())
    ->whereTargetClass()
    ->map(function (AttributeClassItem $item) {
        // Process with my attribute
    });
```
Or the same search but in a specific class:
```php
use Bfg\Attributes\Items\AttributeMethodItem;
use Bfg\Attributes\Attributes;

Attributes::new(MyAttribute::class)
    ->whereClass(MyAnyClassNamespace::class)
    ->whereTargetMethod()
    ->map(function (AttributeMethodItem $item) {
        // Process with my attribute
    });
```
Or you can get all found item attributes:
```php
use Bfg\Attributes\Items\AttributePropertyItem;
use Bfg\Attributes\Attributes;

$collectionOfProperties = Attributes::new(MyAttribute::class)
    ->wherePath(app_path())
    ->whereTargetProperty()
    ->all();

$property = Attributes::new(MyAttribute::class)
    ->wherePath(app_path())
    ->whereTargetProperty()
    ->filter(fn (AttributePropertyItem $propertyItem) => $propertyItem)
    ->first();
```
Or you can get all classes:
```php
use Bfg\Attributes\Attributes;

Attributes::new()
    ->wherePath(app_path())
    ->classes();

// In all directories    
$collectionOfClasses = Attributes::new()->classes();
```

## Supported attributes
The package only supports class attributes, 
such as: `Class`,`Method`,`Property`,`Class constant`, or they are all at once

Check constants:
1. `\Attribute::TARGET_CLASS`
1. `\Attribute::TARGET_METHOD`
1. `\Attribute::TARGET_PROPERTY`
1. `\Attribute::TARGET_CLASS_CONSTANT`
1. `\Attribute::TARGET_ALL` (by Default)
