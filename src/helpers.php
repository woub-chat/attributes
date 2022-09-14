<?php

if (! function_exists('class_in_file')) {
    /**
     * @param  string  $file
     * @return string|null
     */
    function class_in_file(string $file): ?string
    {
        return (new \Bfg\Attributes\ClassGetter())->getClassFullNameFromFile($file);
    }
}
