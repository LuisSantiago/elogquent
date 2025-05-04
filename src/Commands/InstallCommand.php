<?php

namespace Elogquent\Commands;

use Elogquent\ElogquentServiceProvider;
use Elogquent\Exceptions\ElogquentInstallingError;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\select;

#[AsCommand(name: 'elogquent:install')]
class InstallCommand extends Command
{
    public $name = 'elogquent:install';

    public $description = 'My command';

    public function handle(): int
    {
        try {
            $this->comment('Publishing Elogquent Configuration...');
            $this->callSilent('vendor:publish', ['--tag' => 'elogquent-config']);

            $this->comment('Publishing Elogquent Migrations...');
            $this->callSilent('vendor:publish', ['--tag' => 'elogquent-migrations']);

            $this->registerElogquentServiceProvider();
        } catch (Exception $e) {
            throw new ElogquentInstallingError($e->getMessage());
        }

        $this->info('Elogquent installed successfully.');
        $migrate = select(
            label: 'Do you want to run the database migrations now?',
            options: [1 => 'yes', 0 => 'no'],
        );

        if ($migrate === 0) {
            return self::SUCCESS;
        }

        $this->info('Running database migrations...');
        $this->call('migrate');

        return self::SUCCESS;
    }

    protected function registerElogquentServiceProvider(): void
    {
        $this->comment('Publishing Elogquent ServiceProvider...');

        // @phpstan-ignore-next-line
        if (method_exists(ServiceProvider::class, 'addProviderToBootstrapFile') &&
            ServiceProvider::addProviderToBootstrapFile(ElogquentServiceProvider::class)) {
        }
    }
}
