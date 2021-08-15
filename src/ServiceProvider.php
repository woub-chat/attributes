<?php

namespace Bfg\Attributes;

use Bfg\Installer\Processor\DumpAutoloadProcessor;
use Bfg\Installer\Providers\InstalledProvider;

/**
 * Class ServiceProvider
 * @package Bfg\Attributes
 */
class ServiceProvider extends InstalledProvider
{
    /**
     * Set as installed by default.
     * @var bool
     */
    public bool $installed = true;

    /**
     * Executed when the provider is registered
     * and the extension is installed.
     * @return void
     */
    public function installed(): void
    {
        //
    }

    /**
     * Executed when the provider run method
     * "boot" and the extension is installed.
     * @return void
     */
    public function run(): void
    {
        //
    }

    /**
     * Run on dump extension.
     * @param  DumpAutoloadProcessor  $processor
     */
    public function dump(DumpAutoloadProcessor $processor)
    {
        parent::dump($processor);
    }
}

