<?php

namespace Elogquent\Providers;

use Illuminate\Support\ServiceProvider;
use Elogquent\Commands\InstallCommand;
use Elogquent\Commands\ListCommand;
use Elogquent\Contracts\ElogquentRepositoryInterface;
use Elogquent\Repositories\ElogquentDatabaseRepository;

class ElogquentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerPublishing();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ElogquentRepositoryInterface::class, ElogquentDatabaseRepository::class);
        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(
                __DIR__ . '/../config/elogquent.php', 'elogquent'
            );
        }

    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                ListCommand::class,
            ]);
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $publishesMigrationsMethod = method_exists($this, 'publishesMigrations')
                ? 'publishesMigrations'
                : 'publishes';

            $this->{$publishesMigrationsMethod}([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'elogquent-migrations');

            $this->publishes([
                __DIR__ . '/../config/elogquent.php' => config_path('elogquent.php'),
            ], 'elogquent-config');
        }
    }
}
