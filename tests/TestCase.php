<?php

namespace Impono\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Impono\ImponoServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            ImponoServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
    }
}