<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Juaniquillo\BackendComponents\BackendComponentsServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use InteractsWithViews;

    protected function getPackageProviders($app)
    {
        return [
            BackendComponentsServiceProvider::class,
        ];
    }
}
