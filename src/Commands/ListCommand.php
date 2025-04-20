<?php

namespace Elogquent\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'elogquent:list')]
class ListCommand extends Command
{
    public $name = 'elogquent:list';

    public $description = 'My command';

    public function handle(): int
    {
        return self::SUCCESS;
    }
}
