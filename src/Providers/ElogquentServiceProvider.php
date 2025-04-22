<?php

namespace Elogquent\Providers;

use Elogquent\Commands\InstallCommand;
use Elogquent\Commands\RestoreChangeCommand;
use Elogquent\Contracts\ElogquentRepositoryInterface;
use Elogquent\Repositories\ElogquentDatabaseRepository;
use Illuminate\Support\ServiceProvider;

class ElogquentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the service provider.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerPublishing();
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->bind(ElogquentRepositoryInterface::class, ElogquentDatabaseRepository::class);
        if (! app()->configurationIsCached()) {
            $this->mergeConfigFrom(
                __DIR__.'/../../config/elogquent.php', 'elogquent'
            );
        }

    }

    /**
     * Register the package's commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                RestoreChangeCommand::class,
            ]);
        }
    }

    /**
     * Register the package's publishable resources.
     */
    private function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $publishesMigrationsMethod = method_exists($this, 'publishesMigrations')
                ? 'publishesMigrations'
                : 'publishes';

            $this->{$publishesMigrationsMethod}([
                __DIR__.'/../../database/migrations' => database_path('migrations'),
            ], 'elogquent-migrations');

            $this->publishes([
                __DIR__.'/../../config/elogquent.php' => config_path('elogquent.php'),
            ], 'elogquent-config');
        }
    }
}
