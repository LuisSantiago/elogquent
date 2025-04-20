<?php

namespace Elogquent\Commands;

use Illuminate\Console\Command;
use Elogquent\ModelC;
use Symfony\Component\Console\Attribute\AsCommand;
use Illuminate\Support\Facades\DB;

#[AsCommand(name: 'elogquent:list')]
class ListCommand extends Command
{
    public $signature = 'Elogquent:list';

    public $description = 'My command';

    public function handle(): int
    {
        $perPage = 10;
        $page = 1;


        do {
            $users = ElogquentModel::offset(($page - 1) * $perPage)
                ->limit($perPage)
                ->get();

/*            $this->table(
                ['ID', 'Nombre', 'Email'],
                $users->map(fn($u) => [
                    $u->id,
                    $u->changes,
                    $u->model_type
                ])->toArray()
            );*/

            $selected = $this->choice('Which services would you like to install?', $users->map(fn($u) => $u->id. ' ' . $u->model_id. ' '.  $u->changes)->toArray(), 0, null, false);
            dump($selected);

            if ($users->isEmpty()) {
                $this->info("No hay más usuarios.");
                break;
            }

            $this->info("Página {$page}");
            $page++;
            $input = $this->ask("Presiona ENTER para continuar o escribe 'salir' para terminar");

        } while ($input !== 'salir');


        if ($this->confirm('Do you wish to continue?')) {
            $this->info('The command was successful!');
        }

        $this->info('The command was successful!');

        return 0;

        return 0;

        /*$modelToRestore = $this->choice(
            'What is your name?',
            ['Taylor', 'Dayle'],
            $defaultIndex,
            $maxAttempts = null,
            $allowMultipleSelections = false
        );*/

        foreach (DB::table('Elogquent')->simplePaginate() as $Elogquent) {
            $this->line("ID: {$Elogquent->id} - changes: {$Elogquent->changes}");
        }

        $this->table(
            ['Name', 'Email'],
            ElogquentModel::simplePaginate()->toArray()
        );

        return self::SUCCESS;
    }
}
