<?php

namespace Elogquent\Commands;

use Elogquent\Providers\ElogquentServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'elogquent:install')]
class InstallCommand extends Command
{
    public $signature = 'Elogquent:install';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('Publishing Elogquent Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'Elogquent-config']);

        $this->comment('Publishing Elogquent Migrations...');
        $this->callSilent('vendor:publish', ['--tag' => 'Elogquent-migrations']);

        $this->registerElogquentServiceProvider();

        $this->info('Elogquent installed successfully.');
        $this->newLine();
        $this->info('Run "php artisan migrate" to create the Elogquent tables.');

        return self::SUCCESS;
    }

    protected function registerElogquentServiceProvider(): void
    {
        // @phpstan-ignore-next-line
        if (method_exists(ServiceProvider::class, 'addProviderToBootstrapFile') &&
            ServiceProvider::addProviderToBootstrapFile(ElogquentServiceProvider::class)) {
        }
    }
}
