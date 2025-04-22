<?php

namespace Elogquent\Commands;

use Elogquent\Models\ModelChange;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\text;

#[AsCommand(name: 'elogquent:restore-change')]
class RestoreChangeCommand extends Command implements PromptsForMissingInput
{
    protected $signature = 'elogquent:restore-change {id}';

    public $description = 'My command';

    public function handle(): int
    {
        if (!config('elogquent.enabled')) {
            $this->warn('⚙️ Please enable elogquent in config/elogquent.php');
            return self::FAILURE;
        }

        $modelChanged = ModelChange::find((int)$this->argument('id'));
        if (!$modelChanged) {
            $this->warn('🔍 Model change with id "' . $this->argument('id') . '" not found');
            return self::FAILURE;
        }

        $changes = $this->getChanges($modelChanged);
        if (empty($changes)) {
            $this->warn('The changes are the same as the current model.');
            return self::SUCCESS;
        }

        $isDeleted = (bool)$modelChanged->getRelation('modelChanged')->getAttribute('deleted_at');
        $this->table([
            $this->formatWithColor('white', 'Column'),
            $this->formatWithColor('red', $isDeleted ? 'Deleted model' : 'Current model'),
            $this->formatWithColor('green', 'Changed model'),
        ], $changes);


        if ($isDeleted) {
            $this->warn('⚠️ The model has been deleted. Do you want to restore it and the changes?');
        }

        $restore = confirm(label: 'Restore the model with the changes?', default: true);
        if (!$restore) {
            $this->warn('Restore discarded.');
            return self::SUCCESS;
        }

        $modelChanged->restore();
        $this->info('🎉 The model has been restored with the changes.');
        return self::SUCCESS;
    }

    private function formatWithColor(string $color, string $text): string
    {
        return sprintf("<fg=%s>%s</>", $color, $text);
    }

    public function getChanges($modelChanged): array
    {
        $changes = [];
        foreach ($modelChanged->changes as $field => $change) {
            if ($modelChanged->getRelation('modelChanged')->getAttribute($field) === $change) {
                continue;
            }

            $changes[] = [
                $this->formatWithColor('white', $field),
                $this->formatWithColor('red', $modelChanged->getRelation('modelChanged')->getAttribute($field)),
                $this->formatWithColor('green', $change),
            ];
        };

        return $changes;
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'id' => fn() => text(
                label: 'Which model changes id would you like to restore?',
                placeholder: "Example: 1",
                required: true,
            )
        ];
    }
}
