<?php

namespace Elogquent\Commands;

use Elogquent\Jobs\ProcessRestoreChanges;
use Elogquent\Models\ElogquentEntry;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

#[AsCommand(name: 'elogquent:restore-changes')]
class RestoreChangeCommand extends Command
{
    /** @TODO: Create a select with Previous and Next page for exceeded results */
    private const LIMIT_COLUMN_CHANGES = 100;

    protected $signature = 'elogquent:restore-changes {--M|model= : Model class you want to restore} {--I|model-id= : Unique id of the model}';

    public $description = 'Restore a model change';

    public function handle(): int
    {
        if (! config('elogquent.enabled')) {
            return $this->infoAndReturn('️⚙️ Please enable elogquent in config/elogquent.php');
        }

        $modelType = $this->option('model');
        $modelWithChanges = $this->modelsWithChanges();
        if ($modelType && $modelWithChanges->doesntContain($modelType)) {
            return $this->infoAndReturn(sprintf('The model %s has no changes.', $modelType));
        }

        if ($modelWithChanges->isEmpty()) {
            return $this->infoAndReturn('No models with changes found.');
        }

        $modelType ??= select(
            label: 'Select the Model you want to restore:',
            options: $modelWithChanges->toArray(),
            scroll: 15,
            hint: 'You can use the arrow keys to navigate and press <enter>',
        );

        $modelId = $this->option('model-id') ?? text(
            label: 'Which model id would you like to restore?',
            placeholder: "Example: {$modelType} id 1",
            required: true,
            hint: 'Type the id of the model and press <enter>',
        );

        $currentModel = ElogquentEntry::query()
            ->where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->first();

        if (empty($currentModel)) {
            return $this->infoAndReturn(sprintf('🔍 Model %s with id %s not found', $modelType, $modelId));
        }

        $columns = ElogquentEntry::query()
            ->distinct('column')
            ->select('column')
            ->where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->pluck('column');

        if ($columns->isEmpty()) {
            $message = sprintf('The model %s with id %s has no changes.', $modelType, $modelId);

            return $this->infoAndReturn($message);
        }

        $modelColumn = multiselect(
            label: 'Which model attributes you like to restore?',
            options: $columns,
            scroll: 15,
            hint: 'Use the arrow keys to navigate, press <spacebar> to select columns, and press <Enter> to confirm.'
        );

        if (empty($modelColumn)) {
            $message = sprintf('No changes selected for the model %s with id %s.', $modelType, $modelId);

            return $this->infoAndReturn($message);
        }

        $currentModel = ElogquentEntry::query()
            ->where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->first()
            ->modelChanged;

        $changesToUpdate = [];
        foreach ($modelColumn as $column) {
            $currentValue = (string) $currentModel->getAttribute($column);
            $entries = ElogquentEntry::query()
                ->select(['id', 'value', 'created_at'])
                ->where('model_type', $modelType)
                ->where('model_id', $modelId)
                ->where('column', $column)
                ->orderBy('id', 'desc')
                ->limit(self::LIMIT_COLUMN_CHANGES)
                ->get()
                ->mapWithKeys(fn (ElogquentEntry $entry) => [$entry->getAttribute('value') => sprintf('%s (%s)',
                    $this->formatWithColor('green', $entry->getAttribute('value')),
                    $entry->getAttribute('created_at')),
                ])
                ->toArray();

            $changesToUpdate[$column] = select(
                label: "Which change for the column $column would you like to restore?",
                options: $entries,
                scroll: 15,
                hint: sprintf('The current value is: %s', $this->formatWithColor('red', $currentValue)),
            );
        }

        $restore = confirm(label: 'Do you want to restore the changes?');
        if ($restore) {
            ProcessRestoreChanges::dispatch($currentModel, $changesToUpdate)
                ->onConnection(config('elogquent.queue.connection'))
                ->onQueue(config('elogquent.queue.queue'))
                ->delay(config('elogquent.queue.delay'));

            $this->info('🎉 The model has been restored with the changes.');
        }

        return self::SUCCESS;
    }

    private function modelsWithChanges(): Collection
    {
        return ElogquentEntry::query()
            ->select('model_type')
            ->distinct('model_type')
            ->orderBy('model_type')
            ->pluck('model_type', 'model_type');
    }

    private function infoAndReturn(string $message): int
    {
        $this->info($message);

        return self::SUCCESS;
    }

    private function formatWithColor(string $color, string $text): string
    {
        return sprintf('<fg=%s>%s</>', $color, $text);
    }
}
