<?php

namespace Impono\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Impono\ImponoServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string('url', 2083);
            $table->string('disk', 30)->nullable();
            $table->boolean('is_temp')->default(false);
            $table->timestamps();
        });
    }

    protected function getPackageProviders($app): array
    {
        return [
            ImponoServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        config()->set('impono.drivers.aes.key', 'secret');
    }
}