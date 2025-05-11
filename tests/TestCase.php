<?php

namespace Elogquent\Tests;

use Elogquent\ElogquentServiceProvider;
use Elogquent\Models\ElogquentEntry;
use Illuminate\Database\Eloquent\Model;
use Mockery;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(
            __DIR__.'/../database/migrations',
        );
        $this->loadMigrationsFrom(
            __DIR__.'/database/migrations',
        );

    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('elogquent.database_connection', 'sqlite');
        $app['config']->set('elogquent.queue', 'sync');
    }

    protected function getPackageProviders($app): array
    {
        return [
            ElogquentServiceProvider::class,
        ];
    }

    public function mockModel(array $changes = ['name' => 'Luis'], int $key = 1): object
    {
        $model = Mockery::mock(Model::class);
        $model->expects('getKey')
            ->andReturn($key);

        $model->expects('getDirty')
            ->andReturn($changes);

        return $model;
    }

    public function createEntryModel(array $attributes = []): ElogquentEntry
    {
        return ElogquentEntry::factory()
            ->create($attributes);
    }
}
