<?php

namespace Elogquent\Observers;

use Elogquent\Exceptions\ElogquentError;
use Elogquent\Jobs\ProcessClearExceededLimit;
use Elogquent\Jobs\ProcessCreateEntry;
use Elogquent\Jobs\ProcessRemoveDuplicates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Throwable;

class ElogquentObserver
{
    public function updating(Model $model): void
    {
        $modelKey = $model->getKey();
        $modelClass = get_class($model);
        $changes = $this->getChanges($model->getDirty());

        if (is_null($modelKey) || $changes->isEmpty()) {
            return;
        }

        $jobs = [];

        if (config('elogquent.remove_previous_duplicates')) {
            $jobs[] = new ProcessRemoveDuplicates(
                $modelClass,
                $modelKey,
                $changes->toArray(),
            );
        }

        $jobs[] = new ProcessCreateEntry(
            $modelClass,
            $modelKey,
            $changes->toArray(),
        );

        $limit = $this->getLimit($modelClass);
        if ($limit) {
            $jobs[] = new ProcessClearExceededLimit(
                $modelClass,
                $modelKey,
                $limit,
            );
        }

        $this->dispatchJobs($jobs);
    }

    private function dispatchJobs(array $jobs): void
    {
        Bus::chain($jobs)
            ->onConnection(config('elogquent.queue.connection'))
            ->onQueue(config('elogquent.queue.queue'))
            ->delay(config('elogquent.queue.delay'))
            ->catch(function (Throwable $exception) {
                throw new ElogquentError($exception->getMessage());
            })
            ->dispatch();
    }

    private function getLimit(string $modelClass): ?int
    {
        $limit = config('elogquent.changes_limit');
        $limitPerModel = config('elogquent.model_changes_limit');
        if (is_array($limitPerModel) && isset($limitPerModel[$modelClass])) {
            $limit = $limitPerModel[$modelClass];
        }

        return $limit;
    }

    private function getChanges(array $changes): Collection
    {
        $included = config('elogquent.included_columns', []);
        $excluded = config('elogquent.excluded_columns', []);

        $changes = collect($changes);

        if (! empty($included)) {
            $changes = $changes->intersectByKeys(array_flip($included));
        }
        if (! empty($excluded)) {
            $changes = $changes->diffKeys(array_flip($excluded));
        }

        return $changes;
    }
}
