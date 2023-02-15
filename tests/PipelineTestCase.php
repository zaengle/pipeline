<?php

namespace Zaengle\Pipeline\Tests;

use Orchestra\Testbench\TestCase;
use Zaengle\Pipeline\ServiceProvider;

class PipelineTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return ['Pipeline' => 'Zaengle\Pipeline\Facade'];
    }
}
