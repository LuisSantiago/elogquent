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
        return self::SUCCESS;
    }
}
