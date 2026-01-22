<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BackendComponentsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $namespace = self::namespace();

        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name($namespace)
            ->hasViews($namespace);

        include_once 'helpers.php';
    }

    public static function namespace(): string
    {
        return 'backend-component';
    }
}
