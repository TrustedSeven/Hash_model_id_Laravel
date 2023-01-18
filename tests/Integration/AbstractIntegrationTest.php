<?php

namespace Netsells\HashModelIds\Tests\Integration;

use Netsells\HashModelIds\ServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class AbstractIntegrationTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/fixtures/migrations');
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('hash_model_ids.salt', 'test-salt');
    }
}
