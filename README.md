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
\Attributes::find(
    MyAttribute::class, // Find attribute
    function (MyAttribute $attribute, ReflectionMethod $method, ReflectionClass $class) {
        // Process with my attribute
    }, 
    \Attribute::TARGET_METHOD // Optional, for faster scanning
);
```
Or the same search but in a specific folder:
```php
\Attributes::inPath(
    app_path(), // Enter an application path
)->find(
    MyAttribute::class, // Find attribute
    function (MyAttribute $attribute, ReflectionMethod $method, ReflectionClass $class) {
        // Process with my attribute
    }, 
    \Attribute::TARGET_METHOD // Optional, for faster scanning (\Attribute::TARGET_ALL by default)
);
```
Or the same search but in a specific class:
```php
\Attributes::inClass(
    MyAnyClassNamespace::class // Enter any class in which the search will be made
)->find(
    MyAttribute::class, // Find attribute
    function (MyAttribute $attribute, ReflectionMethod $method, ReflectionClass $class) {
        // Process with my attribute
    }, 
    \Attribute::TARGET_METHOD // Optional, for faster scanning (\Attribute::TARGET_ALL by default)
);
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
